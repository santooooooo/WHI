<template>
    <div class="d-flex pa-3" style="position: relative">
        <v-navigation-drawer
            v-model="drawer"
            :mini-variant.sync="mini"
            permanent
            absolute
            class="white--text mt-15 rounded-lg rounded-l-0 drawer"
            style="max-height: 85%"
        >
            <v-list-item class="px-2 white--text">
                <v-list-item-avatar>
                    <v-img :src="$store.state.user.icon"></v-img>
                </v-list-item-avatar>

                <v-list-item-title>{{
                    $store.state.user.name
                }}</v-list-item-title>

                <v-btn icon @click.stop="mini = !mini" class="white--text">
                    <v-icon>mdi-chevron-left</v-icon>
                </v-btn>
            </v-list-item>

            <v-divider class="white"></v-divider>

            <v-list dense>
                <v-list-item link class="white--text">
                    <v-list-item-title>
                        <div @click="section = 'UserUpdate'">
                            ユーザー情報の更新
                        </div>
                    </v-list-item-title>
                </v-list-item>
                <v-list-item link class="white--text">
                    <v-list-item-title>
                        <div @click="section = 'Profile'">プロフィール</div>
                    </v-list-item-title>
                </v-list-item>
                <v-list-item
                    v-for="item in items"
                    :key="item.title"
                    link
                    class="white--text"
                >
                    <v-list-item-title v-if="!item.update">
                        <div
                            @click="
                                changeSection(item.section, item.id, item.title)
                            "
                        >
                            {{ item.title }}
                        </div>
                    </v-list-item-title>
                    <v-btn
                        v-if="item.section === 'Section' && !item.update"
                        @click="check(item.id, item.title)"
                        small
                        class="ml-1 red white--text"
                        >削除</v-btn
                    >
                    <v-btn
                        v-if="item.section === 'Section' && !item.update"
                        @click="item.update = !item.update"
                        small
                        class="ml-1 blue white--text"
                        >変更</v-btn
                    >
                    <v-form
                        v-if="item.update"
                        v-model="valid"
                        class="white pa-1"
                    >
                        <v-text-field
                            required
                            label="変更名"
                            :rules="sectionRules"
                            v-model="newSectionName"
                        >
                        </v-text-field>
                        <v-btn
                            color="green white--text"
                            :disabled="!valid"
                            @click="updateSection(item.title)"
                            >変更する</v-btn
                        >
                        <v-btn
                            color="red white--text"
                            @click="item.update = !item.update"
                            >やめる</v-btn
                        >
                    </v-form>
                </v-list-item>
                <v-form v-model="valid" class="white ma-1 pa-1">
                    <v-text-field
                        required
                        v-model="sectionName"
                        label="追加したい項目"
                        :rules="sectionRules"
                    >
                    </v-text-field>
                    <v-btn
                        color="green white--text"
                        :disabled="!valid"
                        @click="create"
                        >追加</v-btn
                    >
                </v-form>
            </v-list>
        </v-navigation-drawer>

        <div class="mt-15 ml-16 section">
            <user-update v-if="section === 'UserUpdate'" />
            <profile v-if="section === 'Profile'" />
            <sections
                v-if="section === 'Section' && contents.length > 0"
                :section-id="setSectionId"
                :section-name="setSectionName"
                :contents="contents"
            />
        </div>

        <v-overlay :absolute="false" :value="overlay">
            <v-row justify="center" align="center">
                <v-alert color="yellow darken-3 white--text" border="top" dark>
                    "{{ deleteSectionName }}"
                    の項目内の全てのデータが消えてしまいますが、よろしいでしょうか？
                    <v-list-item class="justify-center">
                        <v-btn @click="deleteSection()" class="ma-3 red">
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
import Profile from "./Profile.vue";
import UserUpdate from "./UserUpdate.vue";
import Sections from "./Sections.vue";

export default {
    components: {
        UserUpdate, // ユーザー更新を行う
        Profile, // ユーザーのプロフィール表示・更新を行う
        Sections, // ユーザーが作成したセクションの表示
    },
    data() {
        return {
            // 左端のメニューバーの制御に使用
            drawer: true,
            mini: true,
            // ユーザーページの左側のナビゲーションに表示するアイテム、titleは表示名、sectionは表示するComponentの切り替えに使用
            items: [],
            // 表示するComponentの切り替えに使用
            section: "Profile",
            valid: false,
            // 新たな項目のデータを格納
            sectionName: null,
            sectionRules: [(value) => !!value || "名前が入力されていません"],
            // メニューの表示制御
            drawer: false,
            overlay: false,
            // 項目の削除のデータの格納に使用
            deleteSectionName: null,
            deleteSectionId: null,

            newSectionName: null,
            // 項目の表示に使用
            setSectionId: null,
            setSectionName: null,
            contents: [],
        };
    },
    mounted() {
        // アクセス先がユーザーではない場合は、トップページへ移動させる
        if (
            this.$store.state.user.id !== null &&
            this.$store.state.user.name !== null
        ) {
            this.getSection();
            return;
        }
        return this.$router.push("/");
    },
    methods: {
        // 新たな項目の作成
        create() {
            //　axios.post実行後に作成・取得したthisインスタンスではVuexの機能を使用できないため、ここでthisインスタンスを作成・取得
            const vm = this;
            const data = {
                userName: this.$store.state.user.name,
                sectionName: this.sectionName,
            };
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .post(
                    "/user/" + this.$store.state.user.id + "/sections",
                    data,
                    { headers }
                )
                .then(function (response) {
                    if (response.data != null) {
                        const newSection = {
                            id: response.data["id"],
                            title: response.data["name"],
                            section: "Section", // 項目内のデータの表示に使用
                            update: false, // 項目の更新の際に使用
                        };
                        vm.items.push(newSection);
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
        // 全ての項目の取得
        async getSection() {
            const vm = this;
            const sections = await axios
                .get("/user/" + this.$store.state.user.id + "/sections")
                .then(function (response) {
                    if (response.data != null) {
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
            if (sections != null) {
                for (const section of sections) {
                    const data = {
                        id: section["id"],
                        title: section["name"],
                        section: "Section",
                        update: false,
                    };
                    this.items.push(data);
                }
            }
        },
        // 項目の削除
        deleteSection() {
            const vm = this;
            const data = {
                userId: this.$store.state.user.id,
            };
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .delete("/sections/" + this.deleteSectionId, {
                    data,
                    headers,
                })
                .then(function (response) {
                    if (response.data === "Success") {
                        vm.items.forEach((element) => {
                            if (element.title == vm.deleteSectionName) {
                                const index = vm.items.indexOf(element);
                                vm.items.splice(index, 1);
                            }
                        });
                        // 削除の確認表示の消去
                        vm.drawer = !vm.drawer;
                        vm.overlay = !vm.overlay;
                        // 項目の削除のデータを格納する変数を初期値へ戻す
                        vm.deleteId = null;
                        vm.deleteSection = null;
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
        // 項目名の変更
        updateSection(sectionName) {
            const vm = this;
            const data = {
                userName: this.$store.state.user.name,
                oldSectionName: sectionName,
                newSectionName: this.newSectionName,
            };
            const headers = {
                "User-Id": this.$store.state.user.id,
                "User-Name": this.$store.state.user.name,
            };
            axios
                .put("/sections/" + this.$store.state.user.id, data, {
                    headers,
                })
                .then(function (response) {
                    // リクエストが正常に実行された際、元の項目名を新たな項目名へ書き換える
                    if (response.data !== "Error") {
                        vm.items.forEach((element) => {
                            if (element.title === sectionName) {
                                const index = vm.items.indexOf(element);
                                const updateSection = {
                                    id: response.data["id"],
                                    title: response.data["name"],
                                    section: "Section",
                                    update: false,
                                };
                                vm.items.splice(index, 1, updateSection);
                            }
                        });
                        vm.newSectionName = null;
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
        // 項目の削除を本当にするかどうかの確認
        check(sectionId, sectionName) {
            // 引数に値が渡された場合、それを項目の削除の際に使用
            if (sectionId != null && sectionName != null) {
                this.deleteSectionId = sectionId;
                this.deleteSectionName = sectionName;
            }
            // 削除の確認の表示の制御
            this.drawer = !this.drawer;
            this.overlay = !this.overlay;
        },
        // 項目の切り替え及びその項目のデータの受け渡し
        async changeSection(section, sectionId, sectionName) {
            // 初期値の設定
            this.section = null;
            this.setSectionId = null;
            this.setSectionName = null;
            this.contents = [];

            this.section = section;
            this.setSectionId = sectionId;
            this.setSectionName = sectionName;
            await this.getContents();
        },
        getContents() {
            const vm = this;
            axios
                .get("/user/" + this.$store.state.user.id + "/contents")
                .then(function (response) {
                    if (response.data != null) {
                        for (const content of response.data) {
                            if (content["section_id"] === vm.setSectionId) {
                                const data = {
                                    id: content["id"],
                                    sectionId: content["section_id"],
                                    type: content["type"],
                                    substance: content["substance"],
                                    update: false,
                                };
                                vm.contents.push(data);
                            }
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
};
</script>

<style scoped>
.theme--light.v-navigation-drawer {
    background: rgba(54, 54, 54, 0.9);
}
@media (min-width: 700px) {
    .section {
        width: 90%;
    }
}
@media (max-width: 699px) {
    .section {
        width: 75%;
    }
}
</style>
