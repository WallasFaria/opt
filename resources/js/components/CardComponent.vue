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
                                    {{ place.opening_hours.open_now ? 'Aberto' : 'Fechado' }} Agora
                                </div>
                                <!-- <div class="place-contact"><strong>Telefone:</strong> (22) 2737-4300</div> -->
                            </div>
                        </div>
                        <div class="place-right">
                            <div class="place-time">{{ dateFormated }}</div>
                            <div class="place-popularity place-popularity-low">
                                <div class="place-popularity-text">Movimento<br>Baixo</div>
                            </div>
                        </div>
                    </div>
                    <div class="place-bottom">
                        <div class="place-details">DETALHES</div>
                    </div>
                </div>
                <div v-if="hasPopularity" class="best-hour">
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
            }
        },

        methods: {

        },

        mounted() {
            const days = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab']
            const d = new Date()
            const hour = d.getUTCHours() > 9 ? d.getUTCHours() : '0' + d.getUTCHours()
            this.dateFormated = '' + days[d.getDay()] + ' ' + hour + 'h'

            const day = d.getDay() > 0 ? d.getDay() : 7
            const popularity = this.place.days[day].values;
            this.hasPopularity = popularity.reduce((pv, cv) => pv + cv) > 0

            if (this.hasPopularity) {
                const rangesOfPopularity = {}
            }
        }
    }
</script>
