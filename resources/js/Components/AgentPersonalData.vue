<template>
    <div class="card">
        <div class="w-6/12">
            <div class="flex items-center justify-between mb-7">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        Datos personales
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Aquí puedes ver los datos personales del ingeniero.
                    </p>
                </header>
            </div>

            <div v-if="$page.props.flash.agentCreated" class="alert">
                <p class="flashMsg">
                {{ $page.props.flash.agentCreated }}
                </p> 
            </div>

            <form action="" class="space-y-6" @submit.prevent="update">

                <div>
                    <InputLabel label="Nombre completo" value="Nombre completo" />
                    <TextInput v-model="agent.user_name" />
                    <div v-if="$page.props.errors.updateAgentPersonalData" class="text-dynacom-red text-sm">
                        {{ $page.props.errors.updateAgentPersonalData.user_name }}
                    </div>
                </div>
                <div>
                    <InputLabel label="Correo electrónico" value="Email" />
                    <TextInput v-model="agent.user_email" />
                    <div v-if="$page.props.errors.updateAgentPersonalData" class="text-dynacom-red text-sm">
                        {{ $page.props.errors.updateAgentPersonalData.user_email }}
                    </div>
                </div>
                <div>
                    <InputLabel label="Departamento" value="Departamento" />
                    <select
                        id="department"
                        class="form-control"
                        v-model="agent.department_name"
                    >
                        <option selected disabled value="">
                            Selecciona un departamento
                        </option>

                        <option
                            v-for="(department, index) in departments"
                            :key="index"
                            :value="department.name"
                        >
                            {{ department.name }}
                        </option>
                        
                    </select>
                    <div v-if="$page.props.errors.updateAgentPersonalData" class="text-dynacom-red text-sm">
                        {{ $page.props.errors.updateAgentPersonalData.department_name }}
                    </div>
                </div>
                <div>
                    <InputLabel label="Teléfono" value="Teléfono" />
                    <TextInput v-model="agent.phone" />
                    <div v-if="$page.props.errors.updateAgentPersonalData" class="text-dynacom-red text-sm">
                        {{ $page.props.errors.updateAgentPersonalData.phone }}
                    </div>
                </div>
                <div>
                    <InputLabel label="Móvil" value="Móvil" />
                    <TextInput v-model="agent.mobile" />
                    <div v-if="$page.props.errors.updateAgentPersonalData" class="text-dynacom-red text-sm">
                        {{ $page.props.errors.updateAgentPersonalData.mobile }}
                    </div>
                </div>
                <div>
                    <InputLabel label="Puesto laboral" value="Puesto laboral" />
                    <TextInput v-model="agent.job_title" />
                    <div v-if="$page.props.errors.updateAgentPersonalData" class="text-dynacom-red text-sm">
                        {{ $page.props.errors.updateAgentPersonalData.job_title }}
                    </div>
                </div>

                <div v-if="$page.props.flash.agentUpdated" class="alert">
                    <p class="flashMsg !m-0">
                    {{ $page.props.flash.agentUpdated }}
                    </p> 
                </div>

                <PrimaryButton type="submit" @click="update">
                    <span v-if="submiting" class="flex items-center space-x-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span> Un momento... </span>
                    </span>
                    <span v-else>Actualizar</span>
                </PrimaryButton>
            </form>
        </div>
    </div>
</template>

<script>

import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

export default {
    name: "AgentPersonalData",
    components: {
        PrimaryButton,
        InputLabel,
        TextInput,
        InputError,
    },
    props: {
        agent: {
            type: Object,
            required: true,
        },
        departments: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            submiting: false,
            errors: {},
        };
    },
    methods: {
        update(){
            let t = this;
            let route = "/dashboard/agents/" + t.agent.id + "/personal-data";

            t.submiting = true;

            t.$inertia.post(route, t.agent, {
                preserveScroll: true,
                errorBag: "updateAgentPersonalData",
                onSuccess: () => {
                    t.submiting = false;
                },
                onError: (errors) => {
                    t.submiting = false;
                    t.errors = errors.errors;
                },
            })
        }
    }
}

</script>
