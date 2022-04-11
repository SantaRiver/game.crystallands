<template>
    <game></game>
</template>

<script>

export default {
    props: ['user'],
    data: function() {
        return {
            user : null,
        }
    },
    created () {
        this.user = JSON.parse(this.user);
        console.log(this.user);
    }
}
</script>
