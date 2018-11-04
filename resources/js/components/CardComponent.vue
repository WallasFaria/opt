<template>
    <div class="place-card">
        <div class="place-container">
            <div class="place-image" :style="`background-image: url('${place.img_url}')`"></div>
            <div class="place-text">
                <div class="place-data">
                    <div class="place-mid">
                        <div class="place-left">
                            <div class="place-name">{{ place.name }}</div>
                            <div class="place-info">
                                <div class="place-addr">
                                    <strong>Endereço:</strong> {{ place.final_address }}
                                </div>
                                <div class="place-open">
                                    <strong>Situação:</strong>
                                    {{ isOpen ? 'Aberto' : 'Fechado' }} Agora
                                </div>
                                <!-- <div class="place-contact"><strong>Telefone:</strong> (22) 2737-4300</div> -->
                            </div>
                        </div>
                        <div class="place-right" v-if="showPopularity">
                            <div class="place-time">{{ dateFormated }}</div>
                            <div class="place-popularity" :class="popularityClassName">
                                <div class="place-popularity-text">Movimento<br>{{ rangeOfPopularity }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="place-bottom">
                        <div class="place-details" @click="showStats()">DETALHES</div>
                    </div>
                </div>
                <div class="best-hour">

                </div>
            </div>
        </div>
        <transition name="slide-up">
            <div class="place-timeline" v-if="visibledStats">
                <stats-component
                    :statsData="statsData"
                    :statsLabels="statsLabels" />
            </div>
        </transition>
    </div>
</template>

<script>
    import StatsComponent from "./StatsComponent.vue"
    export default {
        components: {
            "stats-component": StatsComponent
        },
        props: ['place'],
        data() {
            return {
                dateFormated: '',
                hasPopularity: false,
                popularity: '',
                isOpen: null,
                showPopularity: false,
                popularityNow: 0,
                rangeOfPopularity: '',
                popularityClassName: '',
                visibledStats: false,

                statsData: [],
                statsLabels: [],
            }
        },

        methods: {
            rangesOfPopularity() {
                const rangesOfPopularity = [
                    {
                        start: 1,
                        end: 35,
                        label: 'Baixo',
                        className: 'place-popularity-low'
                    },
                    {
                        start: 36,
                        end: 65,
                        label: 'Médio',
                        className: 'place-popularity-mid'
                    },
                    {
                        start: 66,
                        end: 100,
                        label: 'Alta',
                        className: 'place-popularity-high'
                    },
                ]

                for (let i=0; i < rangesOfPopularity.length; i++) {
                    if (this.popularityNow >= rangesOfPopularity[i].start &&
                        this.popularityNow <= rangesOfPopularity[i].end) {
                        this.rangeOfPopularity = rangesOfPopularity[i].label
                        this.popularityClassName = rangesOfPopularity[i].className
                    }
                }
            },
            showStats() {
                this.visibledStats = !this.visibledStats
            }
        },

        mounted() {
            const days = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab']
            const d = new Date()
            const hour = d.getHours() > 9 ? d.getHours() : '0' + d.getHours()
            this.dateFormated = '' + days[d.getDay()] + ' ' + hour + 'h'

            const day = d.getDay() > 0 ? d.getDay() : 7
            const popularity = this.place.days[day].values;

            if (popularity) {
                this.hasPopularity = popularity.reduce((pv, cv) => pv + cv) > 0
                this.statsData = popularity.filter(value => value > 0)
            }

            if (this.place.opening_hours) {
                this.isOpen = this.place.opening_hours.open_now
            }

            if (this.hasPopularity && this.isOpen ||
                this.hasPopularity && this.isOpen === null) {
                this.showPopularity = true
            }

            if (this.showPopularity) {
                this.popularityNow = popularity[d.getHours()]
                this.rangesOfPopularity()
            }
        }
    }
</script>

<style>
    .slide-up-enter-active, .slide-up-leave-active {
        transition: all 0.3s;
    }
    .slide-up-enter, .slide-up-leave-top {
        transform: translateY(-100px);
    }
</style>
