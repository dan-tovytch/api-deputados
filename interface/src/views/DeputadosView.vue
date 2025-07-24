<template>
    <v-container fluid class="pa-6">
        <h1 class="text-h4 font-weight-bold mb-6 text-center">
            Pesquisar Deputado
        </h1>
        <v-row justify="center">
            <v-col cols="12" md="4">
                <v-text-field v-model="searchTerm" label="Pesquisar deputado" clearable outlined dense
                    prepend-inner-icon="mdi-magnify" @keyup.enter="searchDeputy" :loading="isLoading"
                    @click:clear="clearSearch" class="mb-6" />

                <a class="d-flex text-center align-center justify-center" href="/">Ranking</a>

            </v-col>
        </v-row>

        <v-row v-if="hasSearched && deputies.length > 0" dense>
            <v-col v-for="deputy in deputies" :key="deputy.id" cols="12" sm="6" md="6" lg="4">
                <v-card outlined class="pa-4 hoverable pointer d-flex" @click="openModal(deputy)">
                    <v-avatar size="96" class="mr-4">
                        <v-img :src="deputy.urlFoto" />
                    </v-avatar>

                    <div class="flex-grow-1">
                        <h3 class="text-subtitle-1 font-weight-bold mb-1">{{ deputy.nomeEleitoral }}</h3>
                        <p class="text-body-2 mb-1 grey--text">Nome civil: {{ deputy.nomeCivil }}</p>
                        <p class="text-body-2 mb-1 grey--text">{{ deputy.siglaPartido }} - {{ deputy.siglaUf }}</p>
                        <p class="text-body-2 mb-1 grey--text">
                            Sexo: {{ deputy.sexo === 'M' ? 'Masculino' : 'Feminino' }}
                        </p>
                    </div>
                </v-card>
            </v-col>
        </v-row>

        <!-- No results -->
        <v-row justify="center" v-else-if="hasSearched && deputies.length === 0">
            <v-col cols="12" md="6" class="text-center">
                <v-icon size="72" color="grey lighten-1">mdi-account-alert-outline</v-icon>
                <p class="mt-4 grey--text text-subtitle-1 font-italic">
                    Não foi encontrado nenhum deputado.
                </p>
            </v-col>
        </v-row>

        <!-- Deputy details modal -->
        <v-dialog v-model="dialog" max-width="800px" persistent>
            <v-card>
                <v-card-title class="d-flex justify-space-between align-center">
                    <span class="text-h6">{{ selectedDeputy?.nomeEleitoral }}</span>
                    <v-btn variant="flat" icon @click="dialog = false">
                        <v-icon>mdi-close</v-icon>
                    </v-btn>
                </v-card-title>

                <v-card-text>
                    <v-row>
                        <v-col cols="12" md="4" class="text-center">
                            <v-avatar size="160" class="mx-auto mb-4">
                                <v-img :src="selectedDeputy?.urlFoto" />
                            </v-avatar>
                        </v-col>
                        <v-col cols="12" md="8">
                            <v-row dense>
                                <v-col cols="12" sm="6">
                                    <p><strong>Nome civil:</strong> {{ selectedDeputy?.nomeCivil }}</p>
                                    <p><strong>Partido:</strong> {{ selectedDeputy?.siglaPartido }}</p>
                                    <p><strong>UF:</strong> {{ selectedDeputy?.siglaUf }}</p>
                                </v-col>
                                <v-col cols="12" sm="6">
                                    <p><strong>Escolaridade:</strong> {{ selectedDeputy?.escolaridade }}</p>
                                    <p>
                                        <strong>Nascimento:</strong>
                                        {{ formatDate(selectedDeputy?.dataNascimento) }}
                                        ({{ calculateAge(selectedDeputy?.dataNascimento) }} Anos)
                                    </p>
                                </v-col>
                            </v-row>

                            <v-divider class="my-4" />

                            <v-row dense>
                                <v-col cols="12" class="d-flex flex-column gap-2">
                                    <div class="d-flex align-center">
                                        <v-icon color="primary" class="mr-2">mdi-email</v-icon>
                                        <span class="text-body-2">
                                            {{ selectedDeputy?.emailGabinete || 'Não informado' }}
                                        </span>
                                    </div>

                                    <div class="d-flex align-center">
                                        <v-icon color="primary" class="mr-2">mdi-phone</v-icon>
                                        <span class="text-body-2">
                                            {{ selectedDeputy?.telefoneGabinete || 'Não informado' }}
                                        </span>
                                    </div>
                                </v-col>
                            </v-row>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-spacer />
                    <v-btn color="primary" text @click="dialog = false">Fechar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script setup>
import { ref, computed } from "vue";
import { useDeputadoStore } from "@/store/modules/deputados_store";

const store = useDeputadoStore();

const searchTerm = ref("");
const hasSearched = ref(false);
const dialog = ref(false);
const selectedDeputy = ref(null);
const isLoading = ref(false);

const deputies = computed(() => store.deputado || []);

const searchDeputy = async () => {
    if (!searchTerm.value.trim()) return;

    isLoading.value = true;
    await store.SEARCH_DEPUTADO(searchTerm.value);
    isLoading.value = false;

    hasSearched.value = true;
};

const clearSearch = () => {
    searchTerm.value = "";
    store.CLEAR();
    hasSearched.value = false;
    selectedDeputy.value = null;
};

const openModal = (deputy) => {
    selectedDeputy.value = deputy;
    dialog.value = true;
};

const formatDate = (isoDate) => {
    const [year, month, day] = isoDate.split("T")[0].split("-");
    return `${day}/${month}/${year}`;
};

const calculateAge = (isoDate) => {
    const birthDate = new Date(isoDate);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
    return age;
};
</script>

<style scoped>
.pointer {
    cursor: pointer;
}

.hoverable:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease-in-out;
}
</style>
