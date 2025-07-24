import api from "../api";

export default {
  search(event) {
    console.log({ search: event });
    return api.get("deputados/search", {
      params: {
        search: event,
      },
    });
  },
};
