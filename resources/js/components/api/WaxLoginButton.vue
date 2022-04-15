<template>
    <button class="btn btn-wax my-3" @click="login">login via WAX Cloud</button>
</template>

<script>
import * as waxjs from "@waxio/waxjs/dist";

const chain = {
    test: {
        nodeUrl: "https://testnet.waxsweden.org",
        chainId: "f16b1833c747c43682f4386fca9cbb327929334a762755ebec17f6f23c9b8a12",
    },
    prod: {
        nodeUrl: "",
        chainId: "",
    },
};

export default {
    name: "Wax",

    data: () => ({
        wax: null,
        userAccount: null,
        pubKeys: [],
        rpcEndpoint: null,
        chainId: null,
        selectedChain: null,
    }),
    created() {
        this.init();
    },
    methods: {
        init: function () {
            this.selectChain("test");
            this.wax = new waxjs.WaxJS({
                rpcEndpoint: this.rpcEndpoint,
                userAccount: this.userAccount,
                pubKeys: this.pubKeys,
            });
        },
        selectChain: function (type) {
            this.selectedChain = type;
            this.chainId = chain[type]["chain_id"];
            this.rpcEndpoint = chain[type]["nodeUrl"];
        },
        login: async function () {
            await this.wax.login();
            this.getUserAccount();
        },
        getUserAccount: function () {
            //console.log(wax.user.verifyTx);
            this.userAccount = wax.user.account;
            this.keys = wax.user.keys;
        },
    },
};
</script>

<style scoped>
</style>
