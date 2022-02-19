<template>
    <div>
        <h1>プロフィール</h1>

        <v-form v-model="valid" class="white pa-7" width="30%">
            <v-file-input
                accept="image/*"
                label="icon"
                v-model="icon"
                prepend-icon="mdi-camera"
                filled
            ></v-file-input>

            <v-textarea
                label="career"
                v-model="career"
                :rules="nameRules"
                auto-grow
            ></v-textarea>

            <v-text-field
                label="title"
                v-model="title"
                :rules="emailRules"
                auto-grow
            ></v-text-field>

            <v-textarea
                label="text"
                v-model="text"
                :rules="emailRules"
                auto-grow
            ></v-textarea>

            <v-text-field
                label="email"
                v-model="email"
                :rules="emailRules"
            ></v-text-field>

            <v-text-field
                label="twitter"
                v-model="twitter"
                :rules="emailRules"
            ></v-text-field>

            <v-btn color="green white--text" :disabled="!valid" @click="signUp"
                >プロフィールの更新</v-btn
            >
        </v-form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            vaild: false,
            showAlert: false,
            icon: null,
            title: null,
            text: null,
            email: null,
            twitter: null,
        };
    },
};
</script>
