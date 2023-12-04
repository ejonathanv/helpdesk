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
            <select name="" id="" class="form-control !w-6/12" v-model="ticket.contact_id">
                <option selected disabled value="">
                    Selecciona un contacto
                </option>
                <option v-for="contact in contacts" :key="contact.id" :value="contact.id">
                    {{ contact.name }}  <span v-if="contact.job_title"> - {{ contact.job_title }}</span>
                </option>
            </select>
        </div>

        <div v-if="$page.props.flash.ticketContact" class="alert">
            <p class="flashMsg">
            {{ $page.props.flash.ticketContact }}
            </p> 
        </div>

        <PrimaryButton type="submit" @click="updateContact">
            <span v-if="submiting" class="flex items-center space-x-2">
                <i class="fas fa-spinner fa-spin"></i>
                <span>
                    Un momento...
                </span>
            </span>
            <span v-else>Actualizar</span>
        </PrimaryButton>
    </div>
</template>

<script>

    import PrimaryButton from "@/Components/PrimaryButton.vue";

    export default {
        name: "TicketUpdateContact",
        components: {
            PrimaryButton,
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
                    onSuccess: () => {
                        t.submiting = false;
                    },
                });
            }
        }
    }

</script>