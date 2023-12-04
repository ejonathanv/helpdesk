<template>
    <div class="card">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Eventos del ticket
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Aquí puedes ver los eventos del ticket.
                </p>
            </header>
        </div>
    </div>
</template>

<script>
export default {
    name: "TicketEvents",
    props: {
        ticket: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {};
    },
};
</script>
