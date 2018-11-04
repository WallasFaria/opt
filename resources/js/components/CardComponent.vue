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
                                <div class="place-addr"><strong>Endereço:</strong> R. Manoel Teodoro, 28 - Centro, Campos dos Goytacazes - RJ, 28030-490</div>
                                <div class="place-open"><strong>Situação:</strong> Aberto agora</div>
                                <div class="place-contact"><strong>Telefone:</strong> (22) 2737-4300</div>
                            </div>                            
                        </div>
                        <div class="place-right">
                            <div class="place-time">Seg 09h</div>
                            <div class="place-popularity place-popularity-low"><div class="place-popularity-text">Movimento<br>Baixo</div></div>
                        </div>
                    </div>
                    <div class="place-bottom">
                        <div class="place-details">DETALHES</div>
                    </div>
                </div>
                <div class="best-hour">
                    
                </div>
            </div>
        </div>
        <div class="place-timeline">
            <stats-component />
        </div>
    </div>
</template>

<script>
    import StatsComponent from "./StatsComponent.vue"
    export default {
        components: {
            "stats-component": StatsComponent
        },
        props: ['place'],
        methods: {
        },
        data() {
            return {
                data: [40, 20, 12, 39, 10, 40, 39, 80, 40, 20, 12, 11]
            }
        }
    }
</script>
