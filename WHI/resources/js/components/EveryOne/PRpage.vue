<template>
    <div></div>
</template>

<script>
export default {
    data() {
        return {
            iconPath: null,
            career: null,
            title: null,
            text: null,
            email: null,
            twitter: null,
            sections: [],
            contents: [],
        };
    },
    methods: {
        // ユーザーのプロフィールの取得
        async getProfile(id) {
            console.log(id);
            const profile = await axios
                .get("/user/" + id + "/profile")
                .then(function (response) {
                    return response.data;
                })
                .catch(function (error) {
                    alert(
                        "サーバー側の問題により、プロフィールの更新が行えません。問題が解決するまでお待ちください。"
                    );
                    return;
                });

            // 表示するアイコンのURLの取得、なければデフォルト画像のURLを挿入
            this.iconPath =
                profile.icon !== null
                    ? profile.icon
                    : "https://whi.s3.amazonaws.com/asset/FogMan.png";
            this.career = profile.career ?? "";
            this.title = profile.title ?? "";
            this.text = profile.text ?? "";
            this.email = profile.email ?? "";
            this.twitter = profile.twitter ?? "";
        },
        // 全ての項目の取得
        async getSections(id) {
            const vm = this;
            const sections = await axios
                .get("/user/" + id + "/sections")
                .then(function (response) {
                    if (response.data !== null) {
                        return response.data;
                    }
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、現在新規登録が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });

            // 項目をメニューへ表示させる
            if (sections !== null) {
                for (const section of sections) {
                    const data = {
                        id: section["id"],
                        title: section["name"],
                    };
                    this.sections.push(data);
                }
            }
        },
        // Sections.vueへ渡す用のコンテンツデータを取得
        getContents(id) {
            const vm = this;
            axios
                .get("/user/" + id + "/contents")
                .then(function (response) {
                    if (response.data !== null) {
                        for (const content of response.data) {
                            const data = {
                                sectionId: content["section_id"],
                                type: content["type"],
                                substance: content["substance"],
                            };
                            vm.contents.push(data);
                        }
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
    computed: {},
    mounted() {
        const userId = this.$route.params.id;
        this.getProfile(userId);
        this.getSections(userId);
        this.getContents(userId);
    },
};
</script>
