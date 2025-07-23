import api from "../api";

export default {
    get_ranking() {
        return api.get("deputados/ranking")
    }
}