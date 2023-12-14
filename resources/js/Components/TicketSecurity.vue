<template>
    <!-- Archivar ticket -->
    <div class="card mb-6">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Archivar ticket
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Al archivar el ticket, este ya no aparecerá en la lista de
                    tickets activos, sin embargo podrás verlo en la lista de
                    tickets archivados.
                </p>
            </header>
        </div>

        <form action="" @submit.prevent="archiveTicket">
            <PrimaryButton type="submit">
                <i class="fas fa-archive mr-2"></i>
                <span>Archivar ticket</span>
            </PrimaryButton>
        </form>
    </div>

    <!-- Eliminar ticket permanentemente -->
    <div class="card !bg-red-100 !bg-opacity-50">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-dynacom-red">
                    Eliminar ticket
                </h2>

                <p class="mt-1 text-sm text-dynacom-red">
                    Al eliminar el ticket, este ya no aparecerá en la lista de
                    tickets activos y no podrás recuperarlo.
                </p>
            </header>
        </div>

        <form action="" @submit.prevent="deleteTicket">
            <PrimaryButton type="submit">
                <i class="fas fa-trash mr-2"></i>
                <span>Eliminar permanentemente</span>
            </PrimaryButton>
        </form>
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
        },
    },
    components: {
        PrimaryButton,
    },
    data() {
        return {
            message: "",
            archiving: false,
        };
    },
    methods: {
        archiveTicket() {
            let route = "/dashboard/tickets/" + this.ticket.id;
            let confirm = window.confirm(
                "¿Estás seguro de que deseas archivar el ticket?"
            );

            this.archiving = true;

            if (!confirm) {
                this.archiving = false;
                return;
            }

            this.$inertia.post(
                route,
                {
                    _method: "delete",
                    archive: true,
                },
                {
                    preserveScroll: true,
                    onSuccess: (data) => {
                        this.archiving = false;
                        this.$emit("close");
                    },
                }
            );
        },

        deleteTicket() {
            let route = "/dashboard/tickets/" + this.ticket.id;
            let confirm = window.confirm(
                "¿Estás seguro de que deseas eliminar el ticket permanentemente?"
            );

            if (!confirm) {
                return;
            }

            this.$inertia.post(
                route,
                {
                    _method: "delete",
                },
                {
                    preserveScroll: true,
                    onSuccess: (data) => {
                        this.$emit("close");
                    },
                }
            );
        }
    },
};
</script>
