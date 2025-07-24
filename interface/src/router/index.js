import { createRouter, createWebHistory } from 'vue-router'
import RankingView from '@/views/RankingView.vue'
const routes = [
  {
    path: '/',
    name: 'ranking',
    component: RankingView
  },
  {
    path: "/deputados",
    name: "deputados",
    component: () => import("@/views/DeputadosView.vue")
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
