<?php

namespace App\Http\Controllers;

use App\Models\EOSCollection;
use App\Models\EOSPHP\EOSClient;
use App\Models\Log\EosUserTransaction;
use App\Models\Log\UserWalletLog;
use App\Models\User\UserWallet;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class WalletController extends Controller
{
    /**
     * @return Factory|View|Application
     * @throws GuzzleException
     */
    public function index(): Factory|View|Application
    {
        $user = Auth::user();
        $externalBalance = (new EOSClient())->chain()->getCurrencyBalance(
            Config::get('constants.token.token_account'),
            $user['name'],
            Config::get('constants.token.token_symbol')
        );
        $internalBalance = UserWallet::query()->firstWhere('user_id', $user['id'])['balance'];

        return view(
            'components.wallet',
            [
                'wallet' => [
                    'externalBalance' => $externalBalance,
                    'internalBalance' => $internalBalance
                ]
            ]
        );
    }

    /**
     * @throws GuzzleException
     */
    public function deposit(Request $request): JsonResponse
    {
        $user = Auth::user();
        $request->validate(['transaction_id' => 'required']);

        $transactionLog = [
            'user_id' => $user['id'],
            'action' => 'deposit',
            'transaction_id' => $request->get('transaction_id'),
            'status' => 'executed',
        ];

        sleep(2);
        $quantity = EOSCollection::checkTransferToken($request->get('transaction_id'), $user['name']);
        if (!$quantity) {
            $transactionLog['status'] = 'failed';
            (new EosUserTransaction($transactionLog))->save();
            return response()->json(['status' => 'error', 'message' => 'Failed to confirm transaction']);
        }

        (new EosUserTransaction($transactionLog))->save();

        $userWallet = UserWallet::query()->firstWhere('user_id', $user['id']);
        $userWallet['balance'] += $quantity;
        $userWallet->save();

        (new UserWalletLog(
            [
                'user_id' => $user['id'],
                'action' => 'deposit',
                'quantity' => $quantity,
            ]
        ))->save();

        return response()->json(['status' => 'success']);
    }

    public function withdrawal(Request $request): JsonResponse
    {
        $user = Auth::user();
        $request->validate(['quantity' => 'required']);

        $userWallet = UserWallet::query()->firstWhere('user_id', $user['id']);
        if ($userWallet['balance'] < $request->get('quantity')) {
            return response()->json(
                ['status' => 'error', 'message' => 'User balance is less than requested withdrawal']
            );
        }

        $transaction = EOSCollection::transferToken($user['name'], $request->get('quantity'), 'test - withdrawal');

        if ($transaction->transaction_id) {
            (new EosUserTransaction(
                [
                    'user_id' => $user['id'],
                    'action' => 'withdrawal',
                    'transaction_id' => $transaction->transaction_id,
                    'status' => 'executed',
                ]
            ))->save();

            $userWallet['balance'] -= $request->get('quantity');
            $userWallet->save();

            (new UserWalletLog(
                [
                    'user_id' => $user['id'],
                    'action' => 'withdrawal',
                    'quantity' => $request->get('quantity'),
                ]
            ))->save();

            return response()->json(['status' => 'success', 'transaction_id' => $transaction->transaction_id]);
        }

        return response()->json(['status' => 'error', 'Impossible to transfer tokens']);
    }
}
