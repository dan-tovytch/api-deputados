<template>
    <v-container class="my-8">
        <h1 class="text-h4 font-weight-bold mb-6 text-center">
            🏆 TOP 10 Deputados com Mais Despesas
        </h1>

        <v-row dense class="d-flex flex-column align-center">
            <v-col v-for="(item, index) in store.data_ranking" :key="item.id" cols="12" class="mb-6"
                style="max-width: 700px;">
                <v-card class="pa-6 elevation-6"
                    style="display: flex; align-items: center; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.2s ease;"
                    @mouseover="hover = index" @mouseleave="hover = null"
                    :style="hover === index ? 'transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.15);' : ''">
                    <v-avatar size="96" class="me-8" tile>
                        <img :src="item.urlFoto || 'https://via.placeholder.com/96?text=Foto'" alt="Foto do Deputado"
                            style="width: 96px; height: 96px; object-fit: cover; object-position: center; border-radius: 12px;" />
                    </v-avatar>

                    <div style="flex-grow: 1;">
                        <h2 class="text-h6 font-weight-semibold mb-1" style="color: #1E88E5;">
                            {{ index + 1 }}º - {{ item.nomeEleitoral }}
                        </h2>
                        <p class="mb-2" style="font-size: 0.9rem; color: #555;">
                            <strong>Partido:</strong> {{ item.siglaPartido }} ({{ item.siglaUf }})<br />
                            <strong>Nome Civil:</strong> {{ item.nomeCivil }}<br />
                            <strong>Nascimento:</strong> {{ new Date(item.dataNascimento).toLocaleDateString('pt-BR')
                            }}<br />
                            <strong>Contato:</strong>
                            <a v-if="item.emailGabinete" :href="`mailto:${item.emailGabinete}`" style="color: #1E88E5;">
                                {{ item.emailGabinete }}
                            </a>
                            <span v-else>—</span>
                            <br />
                            <span>{{ item.telefoneGabinete || '—' }}</span>
                        </p>

                        <p class="text-subtitle-1 font-weight-bold mb-2" style="color: #424242;">
                            Despesas: R$
                            {{ Number(item.total).toLocaleString('pt-BR', { minimumFractionDigits: 2 }) }}
                        </p>

                        <div>
                            <a v-if="item.redeSocial?.twitter" :href="item.redeSocial.twitter" target="_blank"
                                rel="noopener noreferrer" class="me-4" style="font-size: 1.4rem; color: #1DA1F2;"
                                aria-label="Twitter">
                                🐦
                            </a>
                            <a v-if="item.redeSocial?.facebook" :href="item.redeSocial.facebook" target="_blank"
                                rel="noopener noreferrer" class="me-4" style="font-size: 1.4rem; color: #3b5998;"
                                aria-label="Facebook">
                                📘
                            </a>
                            <a v-if="item.redeSocial?.instagram" :href="item.redeSocial.instagram" target="_blank"
                                rel="noopener noreferrer" style="font-size: 1.4rem; color: #E1306C;"
                                aria-label="Instagram">
                                📸
                            </a>
                        </div>
                    </div>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRankingStore } from '@/store/modules/ranking_store'

const store = useRankingStore()
const hover = ref(null)

onMounted(() => {
    store.FETCH_RANKING()
})
</script>
    