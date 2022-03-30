<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <button class="btn btn-outline-primary" @click="login">login</button>
            </div>
        </div>
    </div>
</template>

<script>

import AnchorLink, {APIError, ChainId} from 'anchor-link'
import AnchorLinkBrowserTransport from 'anchor-link-browser-transport'

import blockchains from './assets/blockchains.json'
import {IdentityProof} from 'eosio-signing-request'

const sessionName = 'crystallands'

export default {
    name: "Anchor",

    data: () => ({
        account: null,
        link: null,
        error: null,
        session: null,
        sessions: null,
        response: null,
        proof: null,
        proofKey: null,
    }),
    async created() {
        await this.establishLink()
    },
    async mounted() {
        //await this.login()
    },
    methods: {
        establishLink: async function () {
            this.link = new AnchorLink({
                chains: blockchains.map(b => ({
                    chainId: b.chainId,
                    nodeUrl: `${b.rpcEndpoints[0].protocol}://${b.rpcEndpoints[0].host}:${b.rpcEndpoints[0].port}`
                })),
                transport: new AnchorLinkBrowserTransport({}),
            })
            const session = await this.link.restoreSession(sessionName)
            const sessions = await this.link.listSessions(sessionName)
            this.error = undefined;
            this.session = session;
            this.sessions = sessions;

            if (session) {
                await this.refreshAccount()
            }
            return this.link
        },
        verifyProof: async function (identity) {
            const chains = blockchains.map(chain => chain.chainId)
            const proof = IdentityProof.from(identity.proof)
            const chain = chains.find(id => ChainId.from(id).equals(proof.chainId))
            if (!chain) {
                throw new Error('Unsupported chain supplied in identity proof')
            }
            let account
            try {
                account = await this.link.client.v1.chain.get_account(proof.signer.actor)
            } catch (error) {
                if (error instanceof APIError && error.code === 0) {
                    throw new Error('No such account')
                } else {
                    throw error
                }
            }
            const auth = account.getPermission(proof.signer.permission).required_auth
            const valid = proof.verify(auth, account.head_block_time)
            if (!valid) {
                throw new Error('Proof invalid or expired')
            }
            const proofKey = proof.recover();
            return {
                account,
                proof,
                proofKey,
                proofValid: valid,
            }
        },
        toSimpleObject: (v) => JSON.parse(JSON.stringify(v)),
        refreshAccount: async function() {
            const {client} = this.link
            const {actor} = this.state.session.auth
            this.account = await client.v1.chain.get_account(actor)
        },
        login: async function() {
            const identity = await this.link.login(sessionName)
            const {
                account,
                proof,
                proofKey,
                proofValid,
            } = await this.verifyProof(identity)

            const sessions = await this.link.listSessions(sessionName)

            this.account = account;
            this.error = undefined;
            this.response = undefined;
            this.proof = proof;
            this.proofKey = String(proofKey);
            this.proofValid = proofValid;
            this.session = identity.session;
            this.sessions = sessions;
            console.log(this.account.get_account());

        }
    }
}
</script>

<style scoped>

</style>
