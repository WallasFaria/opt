<template>
    <div>
        <transition name='slide-up'>
            <search-component
                v-if="showSearch"
                :value="query.name"
                :className="'top'"
                @onKeyPress="setQueryToSearch" />
        </transition>

        <div class="main">
            <div class="content">
                <div class="result">
                    <card-component v-for="(place, index) in places"
                        :key="index"
                        :place="place" />
                </div>
                <div id="loading" v-if="showLoad"><img class="rotate" src="images/icon-logo.png" alt="Opt" /></div>
            </div>
        </div>
    </div>
</template>

<script>
    import SearchComponent from "./SearchComponent.vue";
    import CardComponent from "./CardComponent.vue";

    export default {
        components: {
            'search-component': SearchComponent,
            'card-component': CardComponent,
        },

        async mounted() {
            this.showSearch = true
            this.showLoad = true

            await this.$getLocation({ enableHighAccuracy: true })
                .then(coords => {
                    this.query.lat = coords.lat
                    this.query.lon = coords.lng
                })

            this.search()
        },

        data() {
            return {
                places: [],
                sortOption: ['distance', 'popularity'],
                query:  {
                    name: this.$route.query.q,
                    radius: 5000,
                    lat: 0,
                    lon: 0,
                    sortBy: 'popularity'
                },
                showSearch: false,
                showLoad: false,
            }
        },

        methods: {
            search() {
                this.places = []
                this.showLoad = true;
                
                axios.get('/api/places/', { params: this.query })
                    .then(res => {
                        this.places = res.data;
                        this.showLoad = false;
                    })
            },
            setQueryToSearch(query) {
                this.query.name = query
                this.search()
            }
        }
    }
</script>

<style>
    .slide-up-enter-active {
        transition: all .5s;
    }
    .slide-up-enter {
        transform: translateY(200px);
    }
</style>
