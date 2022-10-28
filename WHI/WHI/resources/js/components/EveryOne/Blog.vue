<template>
    <div class="white--text pa-5">
        <v-list-item class="px-2 white--text" @click="goProfile">
            <v-list-item-avatar>
                <v-img :src="icon"></v-img>
            </v-list-item-avatar>
            <div>
                <v-list-item-title>{{ name }}</v-list-item-title>
                <v-list-item-subtitle
                    class="white--text"
                    v-if="updated !== null"
                    >更新日: {{ updated }}</v-list-item-subtitle
                >
            </div>
        </v-list-item>

        <h1 style="font-size: 2.4rem">{{ title }}</h1>
        <v-md-preview :text="text"></v-md-preview>
    </div>
</template>

<script>
export default {
    data() {
        return {
            // ブログ情報
            id: null,
            title: null,
            text: "",
            updated: null,
            // ブログに表示するユーザー情報
            userId: null,
            name: null,
            icon: null,
        };
    },
    methods: {
        // ブログ情報の取得
        getBlog(id) {
            const vm = this;
            axios
                .get("/blog/" + id)
                .then(function (response) {
                    if (response.data !== null) {
                        const blog = response.data;
                        const userId = blog["user_id"];
                        vm.title = blog["title"];
                        vm.text = blog["text"];
                        vm.updated = blog["updated"];

                        vm.getUserInfo(userId);
                    }
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、現在ブログの取得が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        // ブログに表示するユーザー情報の取得
        getUserInfo(id) {
            const vm = this;
            axios
                .get("/user/" + id)
                .then(function (response) {
                    if (response.data !== null) {
                        const user = response.data;
                        vm.userId = user["id"];
                        vm.name = user["name"];
                        vm.icon = user["icon"];
                    }
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、現在ユーザー情報の取得が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        // PRページへ飛ばす
        goProfile() {
            return this.$router.push("/PRpage/" + this.userId);
        },
    },
    mounted() {
        this.id = this.$route.params.id;
        this.getBlog(this.id);
    },
};
</script>
