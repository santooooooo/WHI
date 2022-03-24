<template>
    <div class="white--text pa-5">
        <h1>{{ title }}</h1>
        <p v-if="updated !== null">更新日: {{ updated }}</p>
        <v-md-preview :text="text"></v-md-preview>
    </div>
</template>

<script>
export default {
    data() {
        return {
            id: null,
            title: null,
            text: "",
            updated: null,
        };
    },
    methods: {
        getBlog(id) {
            const vm = this;
            axios
                .get("/blog/" + id)
                .then(function (response) {
                    if (response.data !== null) {
                        const blog = response.data;
                        vm.title = blog["title"];
                        vm.text = blog["text"];
                        vm.updated = blog["updated"];
                    }
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、現在新規登録が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
    },
    mounted() {
        this.id = this.$route.params.id;
        this.getBlog(this.id);
    },
};
</script>
