<template>
    <div>
        <button class="btn btn-anchor my-3" @click="login">login via Anchor</button>
    </div>
</template>

<script>
import AnchorLink, {APIError, ChainId} from "anchor-link";
import AnchorLinkBrowserTransport from "anchor-link-browser-transport";

import blockchains from "./assets/blockchains.json";
import {IdentityProof} from "eosio-signing-request";

const sessionName = "crystallands";

export default {
    name: "Anchor",

    data: () => ({
        account: {
            name: null,
            permissions: [],
        },
        identity: null,
        link: null,
        error: null,
        session: null,
        sessions: null,
        response: null,
        proof: null,
        proofKey: null,
    }),
    async created() {
        await this.establishLink();
    },
    async mounted() {
        //await this.login()
    },
    methods: {
        establishLink: async function () {
            this.link = new AnchorLink({
                chains: blockchains.map((b) => ({
                    chainId: b.chainId,
                    nodeUrl: `${b.rpcEndpoints[0].protocol}://${b.rpcEndpoints[0].host}:${b.rpcEndpoints[0].port}`,
                })),
                transport: new AnchorLinkBrowserTransport({}),
                verifyProofs: true,
            });
            const session = await this.link.restoreSession(sessionName);

            this.error = undefined;
            this.session = session;

            return this.link;
        },
        verifyProof: async function (identity) {
            // Generate an array of valid chain IDs from the demo configuration
            const chains = blockchains.map(chain => chain.chainId)

            // Create a proof helper based on the identity results from anchor-link
            const proof = IdentityProof.from(identity.proof)

            // Check to see if the chainId from the proof is valid for this demo
            const chain = chains.find(id => ChainId.from(id).equals(proof.chainId))
            if (!chain) {
                throw new Error('Unsupported chain supplied in identity proof')
            }

            // Load the account data from a blockchain API
            let account
            try {
                account = await this.link.client.v1.chain.get_account(proof.signer.actor)
            } catch (error) {
                if (error instanceof APIError && error.code === 0) {
                    throw new Error('No such account', 401)
                } else {
                    throw error
                }
            }

            // Retrieve the auth from the permission specified in the proof
            const auth = account.getPermission(proof.signer.permission).required_auth

            // Determine if the auth is valid with the given proof
            const valid = proof.verify(auth, account.head_block_time)

            // If not valid, throw error
            if (!valid) {
                throw new Error('Proof invalid or expired', 401)
            }

            // Recover the key from this proof
            const proofKey = proof.recover();

            // Return the values expected by this demo application
            return {
                account,
                proof,
                proofKey,
                proofValid: valid,
            }
        },
        login: async function () {
            const identity = await this.link.login(sessionName);
            this.identity = identity;
            const {account, session, proof, proofKey, proofValid} = identity;

            this.account.name = `${account.account_name}`;
            account.permissions.forEach((p) => {
                this.account.permissions.push(`${p.perm_name}`);
            });
            this.proof = proof;
            this.proofKey = String(proofKey);
            this.proofValid = proofValid;
            this.session = session;

            await this.loginRequest();
        },
        loginRequest: async function () {
            const {
                account,
                proof,
                proofKey,
                proofValid,
            } = await this.verifyProof(this.identity)
            const loginRequest = {
                account: account,
                proof: proof,
                proofKey: proofKey,
                proofValid: proofValid,
                authType: 'anchor',
            }
            axios
                .post("login", loginRequest)
                .then((response) => console.log(response));
        }
    },
};
</script>

<style scoped>
</style>
