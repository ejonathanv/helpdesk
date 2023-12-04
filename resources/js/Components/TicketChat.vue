<template>
    <div class="card">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Conversación del ticket
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Aquí puedes ver la conversación con el cliente.
                </p>
            </header>
        </div>
    </div>
</template>

<script>
export default {
    name: "TicketChat",
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
