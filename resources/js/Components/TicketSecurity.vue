<template>
    <div class="card">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Seguridad
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    En esta sección puedes eliminar el ticket ó archivarlo.
                </p>
            </header>
        </div>
    </div>
</template>

<script>

import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
    name: "TicketSecurity",
    props: {
        ticket: {
            type: Object,
            required: true,
        }
    },
    components: {
        PrimaryButton,
    },
    data() {
        return {
            message: "",
        };
    },
    methods: {
        
    }
};
</script>