<template>
    <div class="card mb-7">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Contacto de la cuenta
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Elige un contacto de la cuenta para asignar al ticket.
                </p>
            </header>
        </div>
        <div class="mb-7">
            <select
                name=""
                id=""
                class="form-control !w-6/12"
                v-model="ticket.contact_id"
            >
                <option selected disabled value="">
                    Selecciona un contacto
                </option>
                <option
                    v-for="contact in contacts"
                    :key="contact.id"
                    :value="contact.id"
                >
                    {{ contact.name }}
                    <span v-if="contact.job_title">
                        - {{ contact.job_title }}</span
                    >
                </option>
            </select>

            <!-- We need to display the updateTicketDetails error -->
            <div
                v-if="$page.props.errors.updateContact"
                class="text-dynacom-red text-sm"
            >
                {{ $page.props.errors.updateContact.contact_id }}
            </div>
        </div>

        <div v-if="$page.props.flash.ticketContact" class="alert">
            <p class="flashMsg">
                {{ $page.props.flash.ticketContact }}
            </p>
        </div>

        <PrimaryButton type="submit" @click="updateContact">
            <span v-if="submiting" class="flex items-center space-x-2">
                <i class="fas fa-spinner fa-spin"></i>
                <span> Un momento... </span>
            </span>
            <span v-else>Actualizar</span>
        </PrimaryButton>

        <div v-if="ticket.contact_name">
            <hr class="my-5" />

            <p class="text-sm text-gray-600 mb-4">
                <b>¿El contacto no recuerda su contraseña?</b> Haz clic en el siguiente enlace para enviarle un correo electrónico con sus credenciales.
            </p>

            <form action="">
                <SecondaryButton type="submit" class="space-x-2">
                    <i class="fas fa-key"></i>
                    <span>Enviar credenciales</span>
                </SecondaryButton>
            </form>
        </div>
    </div>
</template>

<script>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

export default {
    name: "TicketUpdateContact",
    components: {
        PrimaryButton,
        SecondaryButton,
    },
    props: {
        ticket: {
            type: Object,
            required: true,
        },
        contacts: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            submiting: false,
        };
    },
    methods: {
        updateContact() {
            const t = this;
            let route = `/dashboard/tickets/${t.ticket.id}/update-contact`;
            let data = {
                contact_id: t.ticket.contact_id,
            };
            t.submiting = true;

            t.$inertia.put(route, data, {
                preserveState: true,
                preserveScroll: true,
                errorBag: "updateContact",
                onSuccess: () => {
                    t.submiting = false;
                },
                onError: () => {
                    t.submiting = false;
                },
            });
        },
    },
};
</script>
