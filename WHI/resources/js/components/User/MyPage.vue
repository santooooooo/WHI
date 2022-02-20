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
                    <v-img
                        :src="$store.state.user.icon"
                    ></v-img>
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
                <v-list-item
                    v-for="item in items"
                    :key="item.title"
                    link
                    class="white--text"
                >
                    <v-list-item-title>
                        <div @click="section = item.sectionName">
                            {{ item.title }}
                        </div>
                    </v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <div class="mt-15 ml-16 section">
            <profile v-if="section === 'Profile'" />
        </div>
    </div>
</template>

<script>
import Profile from "./Profile.vue";

export default {
    components: {
        Profile, // ユーザーのプロフィール表示・更新を行う
    },
    data() {
        return {
            drawer: true,
            mini: true,
            // ユーザーページの左側のナビゲーションに表示するアイテム、titleは表示名、sectionNameは表示するComponentの切り替えに使用
            items: [
                {
                    title: "ユーザー情報の変更",
                    sectionName: "changeUserInfo",
                },
                {
                    title: "プロフィール",
                    sectionName: "Profile",
                },
            ],
            // 表示するComponentの切り替えに使用
            section: "Profile",
        };
    },
    mounted() {
        // アクセス先がユーザーではない場合は、トップページへ移動させる
        if (
            this.$store.state.user.id !== null &&
            this.$store.state.user.name !== null
        ) {
            return;
        }
        return this.$router.push("/");
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
