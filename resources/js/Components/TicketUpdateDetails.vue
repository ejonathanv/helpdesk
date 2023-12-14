<template>
    <div class="card mb-7">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Detalles del ticket
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Aquí puedes ver los detalles del ticket.
                </p>
            </header>
        </div>
        <table class="table mb-7">
            <tbody>
                <tr>
                    <th>Fecha de creación:</th>
                    <td>
                        <span class="text-gray-600">{{
                            ticket.created_at
                        }}</span>
                    </td>
                </tr>
                <tr>
                    <th width="20%">Número de ticket:</th>
                    <td>
                        <span class="text-gray-600">{{ ticket.number }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Cuenta:</th>
                    <td>
                        <span class="text-gray-400 font-bold italic">{{
                            ticket.account_name
                        }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Asunto:</th>
                    <td>
                        <span class="text-gray-600">{{ ticket.subject }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Descripción:</th>
                    <td>
                        <span class="text-gray-600">{{ ticket.content }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Categoría</th>
                    <td>
                        <a
                            href="#"
                            class="text-dynacom-red"
                            @click.prevent="showCategoryModal = true"
                        >
                            {{ ticket.full_category_name }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td>
                        <select
                            name=""
                            id=""
                            class="form-control !w-5/12"
                            v-model="ticket.status_id"
                        >
                            <option value="1">Abierto</option>
                            <option value="2">Resuelto</option>
                            <option value="3">En proceso</option>
                            <option value="4">En espera de cliente</option>
                            <option value="5">Monitoreo</option>
                            <option value="6">Cerrado</option>
                        </select>

                        <!-- We need to display the updateTicketDetails error -->
                        <div v-if="$page.props.errors.updateTicketDetails" class="text-dynacom-red text-sm">
                            {{ $page.props.errors.updateTicketDetails.status_id }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Prioridad:</th>
                    <td>
                        <select
                            name=""
                            id=""
                            class="form-control !w-5/12"
                            v-model="ticket.priority_id"
                        >
                            <option disabled selected value="">
                                Seleccione una prioridad
                            </option>
                            <option value="1">Baja</option>
                            <option value="2">Media</option>
                            <option value="3">Alta</option>
                            <option value="4">Urgente</option>
                        </select>

                        <!-- We need to display the updateTicketDetails error -->
                        <div v-if="$page.props.errors.updateTicketDetails" class="text-dynacom-red text-sm">
                            {{ $page.props.errors.updateTicketDetails.priority_id }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>Severidad:</th>
                    <td>
                        <select
                            name=""
                            id=""
                            class="form-control !w-5/12"
                            v-model="ticket.severity_id"
                        >
                            <option disabled selected value="">
                                Seleccione una severidad
                            </option>
                            <option value="1">Baja</option>
                            <option value="2">Media</option>
                            <option value="3">Alta</option>
                            <option value="4">Urgente</option>
                        </select>
                        <!-- We need to display the updateTicketDetails error -->
                        <div v-if="$page.props.errors.updateTicketDetails" class="text-dynacom-red text-sm">
                            {{ $page.props.errors.updateTicketDetails.severity_id }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div v-if="$page.props.flash.ticketDetails" class="alert">
            <p class="flashMsg">
            {{ $page.props.flash.ticketDetails }}
            </p> 
        </div>

        <PrimaryButton type="submit" @click.prevent="update">
            <span v-if="submiting" class="flex items-center space-x-2">
                <i class="fas fa-spinner fa-spin"></i>
                <span>
                    Un momento...
                </span>
            </span>
            <span v-else>Actualizar</span>
        </PrimaryButton>

        <TicketCategoryModal
            v-if="showCategoryModal"
            :ticket="ticket"
            :categories="categories"
            @close="showCategoryModal = false"
        >
        </TicketCategoryModal>
    </div>
</template>

<script>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TicketCategoryModal from "@/Components/TicketCategoryModal.vue";

export default {
    name: "TicketUpdateDetails",
    components: {
        PrimaryButton,
        TicketCategoryModal,
    },
    props: {
        ticket: {
            type: Object,
            required: true,
        },
        categories: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            showCategoryModal: false,
            submiting: false,
        };
    },
    methods: {
        update() {
            const t = this;
            t.submiting = true;
            t.$inertia.put(`/dashboard/tickets/${t.ticket.id}`, t.ticket, {
                preserveState: true,
                preserveScroll: true,
                errorBag: "updateTicketDetails",
                onSuccess: (data) => {
                    t.$emit("update");
                    t.submiting = false;
                },
                onError: (errors) => {
                    t.submiting = false;
                },
            });
        },
    },
};
</script>
