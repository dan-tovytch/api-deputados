<template>
  <v-container class="my-8">
    <h1 class="text-h4 font-weight-bold mb-6 text-center">
      🏆 Ranking Deputados com Mais Despesas
    </h1>

    <a class="d-flex text-center align-center justify-center" href="/deputados">Pesquisar</a>

    <!-- FILTROS -->
    <v-row dense justify="center" class="mb-6 mt-3">
      <v-col cols="12" sm="5" md="4">
        <v-select
          v-model="selectedParty"
          :items="parties"
          label="Filtrar por Partido"
          clearable
          variant="outlined"
        />
      </v-col>
      <v-col cols="12" sm="5" md="4">
        <v-select
          v-model="selectedState"
          :items="states"
          label="Filtrar por UF"
          clearable
          variant="outlined"
        />
      </v-col>
    </v-row>

    <!-- LISTA DE DEPUTADOS -->
    <v-row dense class="d-flex flex-column align-center">
      <v-col
        v-for="(deputy, index) in paginatedList"
        :key="deputy.id"
        cols="12"
        class="mb-6"
        style="max-width: 700px;"
      >
        <v-card
          class="pa-6 elevation-6"
          style="display: flex; align-items: center; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); transition: transform 0.2s ease;"
          @mouseover="hoverIndex = index"
          @mouseleave="hoverIndex = null"
          :style="hoverIndex === index ? 'transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.15);' : ''"
        >
          <v-avatar size="96" class="me-8" tile>
            <img
              :src="deputy.urlFoto || 'https://via.placeholder.com/96?text=Foto'"
              alt="Foto do Deputado"
              style="width: 96px; height: 96px; object-fit: cover; object-position: center; border-radius: 12px;"
            />
          </v-avatar>

          <div style="flex-grow: 1;">
            <h2 class="text-h6 font-weight-semibold mb-1" style="color: #1E88E5;">
              {{ (currentPage - 1) * itemsPerPage + index + 1 }}º - {{ deputy.nomeEleitoral }}
            </h2>
            <p class="mb-2" style="font-size: 0.9rem; color: #555;">
              <strong>Partido:</strong> {{ deputy.siglaPartido }} ({{ deputy.siglaUf }})<br />
              <strong>Nome Civil:</strong> {{ deputy.nomeCivil }}<br />
              <strong>Nascimento:</strong> {{ new Date(deputy.dataNascimento).toLocaleDateString('pt-BR') }}<br />
              <strong>Contato:</strong>
              <a
                v-if="deputy.emailGabinete"
                :href="`mailto:${deputy.emailGabinete}`"
                style="color: #1E88E5;"
              >
                {{ deputy.emailGabinete }}
              </a>
              <span v-else>—</span>
              <br />
              <span>{{ deputy.telefoneGabinete || '—' }}</span>
            </p>

            <p class="text-subtitle-1 font-weight-bold mb-2" style="color: #424242;">
              Despesas: R$
              {{ Number(deputy.total).toLocaleString('pt-BR', { minimumFractionDigits: 2 }) }}
            </p>
          </div>
        </v-card>
      </v-col>
    </v-row>

    <v-row justify="center">
      <v-col cols="auto">
        <v-pagination
          v-model="currentPage"
          :length="totalPages"
          total-visible="5"
          prev-icon="mdi-chevron-left"
          next-icon="mdi-chevron-right"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup>
import { onMounted, ref, computed, watch } from 'vue'
import { useRankingStore } from '@/store/modules/ranking_store'

const store = useRankingStore()

const hoverIndex = ref(null)

const selectedParty = ref(null)
const selectedState = ref(null)

const currentPage = ref(1)
const itemsPerPage = 10

onMounted(() => {
  store.FETCH_RANKING()
})

const parties = computed(() => {
  const uniqueParties = new Set(store.data_ranking.map(item => item.siglaPartido).filter(Boolean))
  return Array.from(uniqueParties).sort()
})

const states = computed(() => {
  const uniqueStates = new Set(store.data_ranking.map(item => item.siglaUf).filter(Boolean))
  return Array.from(uniqueStates).sort()
})

const filteredList = computed(() => {
  return store.data_ranking.filter(item => {
    const matchParty = selectedParty.value ? item.siglaPartido === selectedParty.value : true
    const matchState = selectedState.value ? item.siglaUf === selectedState.value : true
    return matchParty && matchState
  })
})

const totalPages = computed(() =>
  Math.ceil(filteredList.value.length / itemsPerPage)
)

const paginatedList = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  const end = start + itemsPerPage
  return filteredList.value.slice(start, end)
})

watch(currentPage, (newPage) => {
  store.FETCH_RANKING(newPage)
})
</script>
