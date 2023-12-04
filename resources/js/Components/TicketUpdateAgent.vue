<template>
    <div class="card mb-7">
        <div class="flex items-center justify-between mb-7">
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    Ingeniero asignado al ticket
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Elige un ingeniero para asignar al ticket.
                </p>
            </header>
        </div>
        <div class="mb-7">
            <select name="" id="" class="form-control !w-6/12" v-model="ticket.agent_id">
                <option disabled seleted value="">
                    Selecciona un ingeniero
                </option>
                <option
                    v-for="agent in agents"
                    :key="agent.id"
                    :value="agent.id"
                >
                    {{ agent.user.name }} <span v-if="agent.job_title"> - {{ agent.job_title }}</span>
                </option>
            </select>
        </div>

        <div v-if="$page.props.flash.ticketAgent" class="alert">
            <p class="flashMsg">
            {{ $page.props.flash.ticketAgent }}
            </p> 
        </div>

        <PrimaryButton type="submit" @click="update">
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
    name: "TicketUpdateAgent",

    components: {
        PrimaryButton,
    },

    props: {
        ticket: {
            type: Object,
            required: true,
        },
        agents: {
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
        update() {
            let t = this;
            let route = `/dashboard/tickets/${t.ticket.id}/update-agent`;
            let data = {
                agent_id: t.ticket.agent_id,
            };
            t.submiting = true;
            t.$inertia.put(route, data, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: (data) => {
                    t.submiting = false;
                },
            });
        },
    }
};
</script>
