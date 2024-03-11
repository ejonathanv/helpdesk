<template>
    <div class="card">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Historial del ticket
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Aquí puedes ver el historial del ticket.
                </p>
            </header>
        </div>

        <div>
            <table class="table">
                <tbody>
                    <tr v-for="history in histories" :key="history.id">
                        <td>
                            <p class="font-bold text-sm">
                                {{ history.description }}
                            </p>
                            
                            <template v-if="history.user">
                                <p class="text-gray-400 text-xs font-bold">
                                    Por: {{ history.user.name }}
                                </p>
                                <p class="text-gray-400 text-xs font-bold">
                                    Fecha: {{ history.created_at }}
                                </p>
                            </template>

                            <template v-else>
                                <p class="text-gray-400 text-xs font-bold">
                                    Por: {{ history.contact_name }}
                                </p>
                                <p class="text-gray-400 text-xs font-bold">
                                    Fecha: {{ history.created_at }}
                                </p>
                            </template>

                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: "TicketHistory",
    props: {
        ticket: {
            type: Object,
            required: true,
        },
        histories: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {};
    },
};
</script>
