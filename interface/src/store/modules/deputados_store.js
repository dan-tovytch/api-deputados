// src/stores/deputado.js (ou .ts, dependendo do seu setup)
import deputados from "@/services/modules/deputados";
import { defineStore } from "pinia";

export const useDeputadoStore = defineStore("deputados", {
  state: () => ({
    deputado: [],
  }),

  actions: {
    async SEARCH_DEPUTADO(term) {
      try {
        const response = await deputados.search(term);
        if (response && response.data && response.data.data) {
          this.deputado = response.data.data || [];
        } else {
          this.deputado = [];
        }
      } catch (error) {
        console.error("Erro na busca de deputado:", error);
        this.deputado = [];
      }
    },

    CLEAR() {
      this.deputado = [];
    },
  },
});
