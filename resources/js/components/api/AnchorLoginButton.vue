<template>
  <div>
    <button class="btn btn-anchor my-3" @click="login">login via Anchor</button>
    <button class="btn btn-anchor my-3" @click="loginRequest">login request</button>
    <button class="btn btn-anchor my-3" @click="test">login request</button>
  </div>
</template>

<script>
import AnchorLink, { APIError, ChainId } from "anchor-link";
import AnchorLinkBrowserTransport from "anchor-link-browser-transport";

import blockchains from "./assets/blockchains.json";
import { IdentityProof } from "eosio-signing-request";

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
      this.sessions = sessions;

      return this.link;
    },
    login: async function () {
      const identity = await this.link.login(sessionName);
      this.identity = identity;
      const authTrasaction = identity.proof;
      console.log("identity", identity.proof.transaction);
      const { account, session, proof, proofKey, proofValid } = identity;

      this.account.name = `${account.account_name}`;
      account.permissions.forEach((p) => {
        this.account.permissions.push(`${p.perm_name}`);
      });
      this.proof = proof;
      this.proofKey = String(proofKey);
      this.proofValid = proofValid;
      this.session = session;

      this.loginRequest(authTrasaction)
    },
    loginRequest: function (data) {
      axios
        .post("console", data)
        .then((response) => console.log(response));
    },
    test: function (data) {
      axios
        .post("http://127.0.0.1:8888/v1/chain/get_account", {account_name: "fflro.wam"})
        .then((response) => console.log(response));
    },
  },
};
</script>

<style scoped>
</style>
