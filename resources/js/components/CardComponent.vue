<template>
    <div class="place-card">
        <div class="place-container">
            <div class="place-image" style="background-image: url('images/placeholder.jpg')"></div>
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
                            <div class="place-popularity place-popularity-low">
                                <div class="place-popularity-text">Movimento<br>{{ rangeOfPopularity }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="place-bottom">
                        <div class="place-details">DETALHES</div>
                    </div>
                </div>
                <div class="best-hour">
                    <div class="hour-circle">

                    </div>
                    <div class="place-status"></div>
                </div>
            </div>
        </div>
        <div class="place-timeline">
            <div class="place-timeline--scroller">
                <span class="place-timeline--text"></span>
            </div>
            <div class="place-timeline--chart">

            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props: ['place'],
        data() {
            return {
                dateFormated: '',
                hasPopularity: false,
                popularity: '',
                isOpen: null,
                showPopularity: false,
                popularityNow: 0,
                rangeOfPopularity: ''
            }
        },

        methods: {
            rangesOfPopularity() {
                const rangesOfPopularity = [
                    {
                        start: 1,
                        end: 35,
                        label: 'Baixo',
                    },
                    {
                        start: 36,
                        end: 65,
                        label: 'Médio',
                    },
                    {
                        start: 66,
                        end: 100,
                        label: 'Alta',
                    },
                ]

                for (let i=0; i < rangesOfPopularity.length; i++) {
                    if (this.popularityNow >= rangesOfPopularity[i].start &&
                        this.popularityNow <= rangesOfPopularity[i].end) {
                        this.rangeOfPopularity = rangesOfPopularity[i].label
                    }
                }
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
