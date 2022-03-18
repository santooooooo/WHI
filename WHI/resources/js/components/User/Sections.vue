<template>
    <div class="white--text">
        <h1>{{ sectionName }}</h1>
        <v-divider class="grey"></v-divider>

        <v-menu offset-y>
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    color="primary"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    :disabled="!addButton"
                    class="ma-3"
                >
                    コンテンツを追加する
                </v-btn>
            </template>
            <v-list>
                <v-list-item v-for="(item, index) in items" :key="index">
                    <v-list-item-title
                        style="cursor: pointer"
                        @click="openForm(item.title)"
                        >{{ item.title }}</v-list-item-title
                    >
                </v-list-item>
            </v-list>
        </v-menu>

        <v-form v-model="valid" class="white pa-3" v-if="form">
            <v-textarea
                :label="type"
                v-model="content"
                :rules="contentRules"
                auto-grow
                required
                type="string"
                rows="3"
            ></v-textarea>

            <div class="d-flex">
                <v-btn
                    color="green white--text"
                    :disabled="!valid"
                    @click="create"
                    >コンテンツの追加</v-btn
                >
                <v-btn color="red white--text" @click="closeForm" class="ml-3"
                    >閉じる</v-btn
                >
            </div>
        </v-form>

        <h2 class="mt-2 ml-3">全てのコンテンツ</h2>
        <v-list-item
            v-for="content in contents"
            :key="content.id"
            class="ml-3 mt-2 mb-2 pt-3 white--text row"
            style="display: grid"
        >
            <v-list-item-title
                v-if="content.type === 'text'"
                max-width="100%"
                style="white-space: pre-line"
            >
                <div>
                    {{ content.substance }}
                </div>
            </v-list-item-title>
            <v-card
                max-width="90%"
                :href="content.substance"
                v-if="content.type === 'url'"
                class="black--text ogp-pozition"
            >
                <v-img
                    contain
                    class="ogp-img"
                    :src="ogpImage(content.id)"
                ></v-img>
                <div>
                    <v-card-title>{{ ogpTitle(content.id) }} </v-card-title>
                    <v-card-text>{{ ogpDescription(content.id) }} </v-card-text>
                    <v-card-text>{{ ogpUrl(content.id) }} </v-card-text>
                </div>
            </v-card>
            <div class="d-flex justify-end mt-2" style="box-sizing: inherit">
                <v-btn
                    v-if="!content.update"
                    @click="check(content.id)"
                    class="ml-1 red white--text"
                    >削除</v-btn
                >
                <v-btn
                    v-if="!content.update"
                    @click="content.update = !content.update"
                    class="ml-1 blue white--text"
                    >変更</v-btn
                >
            </div>
            <v-form v-if="content.update" v-model="valid" class="white pa-1">
                <v-textarea
                    required
                    label="変更内容"
                    :rules="contentRules"
                    v-model="updateSubstance"
                >
                </v-textarea>
                <v-btn
                    color="green white--text"
                    :disabled="!valid"
                    @click="update(content.id)"
                    >変更する</v-btn
                >
                <v-btn
                    color="red white--text"
                    @click="content.update = !content.update"
                    >やめる</v-btn
                >
            </v-form>
        </v-list-item>

        <v-overlay :absolute="false" :value="overlay">
            <v-row justify="center" align="center">
                <v-alert color="yellow darken-3 white--text" border="top" dark>
                    本当にこのコンテンツのデータを消去しますか？
                    <v-list-item class="justify-center">
                        <v-btn @click="deleteContent()" class="ma-3 red">
                            <v-list-item-title class="subtitle-1 pa-5">
                                削除する
                            </v-list-item-title>
                        </v-btn>

                        <v-btn @click="check()" class="ma-3 green">
                            <v-list-item-title class="subtitle-1 pa-5">
                                戻る
                            </v-list-item-title>
                        </v-btn>
                    </v-list-item>
                </v-alert>
            </v-row>
        </v-overlay>
    </div>
</template>

<script>
export default {
    props: {
        sectionId: {
            type: Number,
            required: true,
        },
        sectionName: {
            type: String,
            required: true,
        },
        contents: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            // フォームのバリデーションに使用
            valid: true,
            contentRules: [(value) => !!value || "何も入力されていません"],
            content: null,
            type: null,
            items: [{ title: "text" }, { title: "url" }, { title: "ブログ" }],
            form: false,
            addButton: true,
            updateSubstance: null,
            // 削除の際の確認表示の制御に使用
            drawer: false,
            overlay: false,
            deleteContentId: null,
            ogps: [],
        };
    },
    methods: {
        openForm(type) {
            if ((type === "text") | (type === "url")) {
                this.type = type;
                this.addButton = !this.addButton;
                this.form = true;
                return;
            }
            return;
        },
        closeForm() {
            this.addButton = !this.addButton;
            this.form = !this.form;
        },
        create() {
            //　axios.post実行後にthisインスタンスは使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            const data = {
                userId: this.$store.state.user.id,
                sectionId: this.sectionId,
                type: this.type,
                substance: this.content,
            };
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .post(
                    "/user/" + this.$store.state.user.id + "/contents",
                    data,
                    { headers }
                )
                .then(function (response) {
                    if (response.data !== null) {
                        const newContent = {
                            id: response.data["id"],
                            sectionId: response.data["section_id"],
                            type: response.data["type"],
                            substance: response.data["substance"],
                            update: false,
                        };
                        vm.contents.push(newContent);
                        vm.content = null;
                        vm.form = !vm.form;
                        vm.addButton = !vm.addButton;

                        vm.changeOgp();
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
        // コンテンツの削除を本当にするかどうかの確認
        check(id) {
            // 変数の初期化
            this.deleteContentId = null;
            // 引数に値が渡された場合、それをコンテンツの削除の際に使用
            if (id != null) {
                this.deleteContentId = id;
            }
            // 削除の確認の表示の制御
            this.drawer = !this.drawer;
            this.overlay = !this.overlay;
        },
        update(contentId) {
            const vm = this;
            const data = {
                userId: this.$store.state.user.id,
                sectionId: this.sectionId,
                substance: this.updateSubstance,
            };
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .put("/contents/" + contentId, data, {
                    headers,
                })
                .then(function (response) {
                    // リクエストが正常に実行された際、元の項目名を新たな項目名へ書き換える
                    if (response.data !== "Error") {
                        vm.contents.forEach((element) => {
                            if (element.id === contentId) {
                                const index = vm.contents.indexOf(element);
                                const updateContent = {
                                    id: response.data["id"],
                                    sectionId: response.data["section_id"],
                                    type: response.data["type"],
                                    substance: response.data["substance"],
                                    update: false,
                                };
                                vm.contents.splice(index, 1, updateContent);
                            }
                        });
                        vm.changeOgp();
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
        deleteContent() {
            const vm = this;
            const data = {
                userId: this.$store.state.user.id,
                sectionId: this.sectionId,
            };
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .delete("/contents/" + this.deleteContentId, {
                    data,
                    headers,
                })
                .then(function (response) {
                    vm.contents.forEach((element) => {
                        if (element.id == vm.deleteContentId) {
                            const index = vm.contents.indexOf(element);
                            vm.contents.splice(index, 1);
                        }
                    });
                    // 削除の確認表示の消去
                    vm.drawer = !vm.drawer;
                    vm.overlay = !vm.overlay;
                    // 項目の削除のデータを格納する変数を初期値へ戻す
                    vm.deleteContentId = null;
                    return;
                })
                .catch(function (error) {
                    // サーバ側から何らかのエラーが発せられた場合
                    alert(
                        "サーバー側の問題により、現在新規登録が行えません。問題の対処が完了するまでお待ちください。"
                    );
                });
        },
        getOgp(id, url) {
            //　axios.post実行後にthisインスタンスは使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            const data = {
                url: url,
            };
            axios
                .post("/ogp/", data)
                .then(function (response) {
                    if (response.data !== "Error") {
                        const ogpInfo = response.data;
                        const newOgp = {
                            id: id,
                            title: ogpInfo["title"],
                            description: ogpInfo["description"],
                            url: ogpInfo["url"],
                            image: ogpInfo["image"],
                        };
                        vm.ogps.push(newOgp);
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
        getOgpObj(id) {
            let result = null;
            this.ogps.forEach((ogp) => {
                if (ogp.id == id) {
                    result = ogp;
                }
            });
            return result;
        },
        changeOgp() {
            this.contents.forEach((content) => {
                if (content.type == "url") {
                    this.getOgp(content.id, content.substance);
                }
            });
        },
    },
    mounted() {
        this.changeOgp();
    },
    computed: {
        ogpTitle() {
            return function (id) {
                const ogp = this.getOgpObj(id);
                if (ogp === null) {
                    return null;
                }
                return ogp.title;
            };
        },
        ogpDescription() {
            return function (id) {
                const ogp = this.getOgpObj(id);
                if (ogp === null) {
                    return null;
                }
                return ogp.description;
            };
        },
        ogpUrl() {
            return function (id) {
                const ogp = this.getOgpObj(id);
                if (ogp === null) {
                    return null;
                }
                return ogp.url;
            };
        },
        ogpImage() {
            return function (id) {
                const ogp = this.getOgpObj(id);
                if (ogp === null) {
                    return null;
                }
                return ogp.image;
            };
        },
    },
};
</script>
<style scoped>
* {
    overflow-wrap: break-word;
    word-wrap: break-word;
}
.row {
    display: flex;
    flex-direction: row;
}
.row > * {
    min-width: 0;
}
@media (min-width: 800px) {
    .ogp-pozition {
        display: flex;
    }
    .ogp-img {
        max-width: 50%;
    }
}
@media (max-width: 799px) {
    .ogp-img {
        max-width: 100%;
    }
}
</style>
