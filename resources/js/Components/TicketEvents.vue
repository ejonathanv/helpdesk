<template>
    <template v-if="user">
        <!-- Indicadores de eventos -->
        <div class="flex items-stretch space-x-6 mb-6">
            <!-- Atencion en sitio -->
            <div class="card w-1/3 !bg-blue-100 !py-3 flex flex-col">
                <header class="mb-3">
                    <h2 class="text-lg font-medium text-gray-900">
                        Atención en sitio
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Tiempo total invertido en atención en sitio.
                    </p>
                </header>

                <h3 class="space-x-2 mt-auto">
                    <span class="text-2xl font-bold">
                        {{ ticket.site_attention_time }}
                    </span>
                </h3>
            </div>
            <!-- Atencion remota -->
            <div class="card w-1/3 !bg-red-100 !py-3 flex flex-col">
                <header class="mb-3">
                    <h2 class="text-lg font-medium text-gray-900">
                        Atención remota
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Tiempo total invertido en atención remota.
                    </p>
                </header>

                <h3 class="space-x-2 mt-auto">
                    <span class="text-2xl font-bold">
                        {{ ticket.remote_attention_time }}
                    </span>
                </h3>
            </div>
            <!-- Total invertido -->
            <div class="card w-1/3 !bg-green-100 !py-3 flex flex-col">
                <header class="mb-3">
                    <h2 class="text-lg font-medium text-gray-900">
                        Tiempo total invertido
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Tiempo total invertido en el ticket.
                    </p>
                </header>

                <h3 class="space-x-2 mt-auto">
                    <span class="text-2xl font-bold">
                        {{ ticket.total_attention_time }}
                    </span>
                </h3>
            </div>
        </div>
    </template>

    <!-- Tabla de eventos -->
    <EventsTable :ticket="ticket" :events="events"></EventsTable>
</template>

<script>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import EventsTable from "@/Components/EventsTable.vue";

export default {
    name: "TicketEvents",
    components: {
        PrimaryButton,
        InputLabel,
        TextInput,
        EventsTable,
    },
    props: {
        systemUser: {
            type: Boolean,
            required: true,
            default: false,
        },
        ticket: {
            type: Object,
            required: true,
        },
        events: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            user: this.$page.props.auth.user,
        };
    },
};
</script>
