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

import AnchorLink, {ChainId, LinkSession} from 'anchor-link'
import AnchorLinkBrowserTransport from 'anchor-link-browser-transport'
import blockchains from './assets/blockchains.json'


export default {
    name: "Anchor",

    data: () => ({
        apiNode: null,
        chainId: null,
        selectedChain: null,
        link: null,
    }),
    created() {
        this.selectChain('test');
        this.link = this.getLink()
        console.log(blockchains);
        //this.login()
    },
    methods: {
        selectChain: function (type) {
            this.selectedChain = type;
            this.chainId = chain[type]['chainId'];
            this.apiNode = chain[type]['nodeUrl'];
        },
        getLink: function() {
            return new AnchorLink({
                transport: new AnchorLinkBrowserTransport(),
                chains: [
                    {
                        chainId: 'aca376f206b8fc25a6ed44dbdc66547c36c6c33e3a119ffbeaef943642f0e906',
                        nodeUrl: 'https://eos.greymass.com',
                    },
                ],
            })
        },
        verifyProof: async (identity) => {
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
        login: async () => {
            // try {
            // Use the anchor-link login method with the chain id to establish a session
            const identity = await this.link.login('anchor-link-demo-multipass')

            // (OPTIONAL) Verify the identity proof
            const {
                account,
                proof,
                proofKey,
                proofValid,
            } = await this.verifyProof(identity)

            // Retrieve a list of all available sessions to update demo state
            const sessions = await this.link.listSessions('anchor-link-demo-multipass')
            // Update state with the current session and all available sessions
            this.setState({
                account,
                error: undefined,
                response: undefined,
                proof,
                proofKey: String(proofKey),
                proofValid,
                session: identity.session,
                sessions,
            })
            // } catch(e) {
            //   console.log(e)
            // }
        }
    }
}
</script>

<style scoped>

</style>
