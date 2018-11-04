<template>
    <div class="search" :class="className">
        <button v-if="className == 'top'" class="btn-back" @click="goBack()">
            <img :src="require('../../svg/back-arrow.svg')" alt="back">
        </button>
        <input @keypress.enter="$emit('onKeyPress', query)"
            v-model="query"
            type="text"
            placeholder="Pesquisar">
    </div>
</template>

<script>
    export default {
        props: ['value', 'className'],

        data() {
            return {
                query: this.value
            }
        },

        methods: {
            goBack () {
                window.history.length > 1
                    ? this.$router.go(-1)
                    : this.$router.push('/')
            }
        }
    }
</script>
