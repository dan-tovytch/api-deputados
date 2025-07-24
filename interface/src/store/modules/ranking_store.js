import ranking from "@/services/modules/ranking";
import { defineStore } from "pinia";

export const useRankingStore = defineStore("ranking", {
  state: () => ({
    data_ranking: [],
    loading: true,
  }),

  actions: {
    async FETCH_RANKING() {
      try {
        const response = await ranking.get_ranking();
        if (
          response.data &&
          response.data.data &&
          Array.isArray(response.data.data)
        ) {
          this.data_ranking = response.data.data; 
        } else {
          console.error("Resposta não tem o array esperado:", response.data);
          this.data_ranking = [];
        }
      } catch (error) {
        console.error("Erro ao buscar o ranking");
      } finally {
        this.loading = false;
      }
    },
  },
});
