<template>
    <div class="card">
        <div class="w-6/12">
            <div class="flex items-center justify-between mb-7">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        Información general
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        Aquí puedes ver la información general del departamento
                    </p>
                </header>
            </div>

            <div v-if="$page.props.flash.departmentCreated" class="alert">
                <p class="flashMsg">
                {{ $page.props.flash.departmentCreated }}
                </p> 
            </div>

            <form action="" class="space-y-6" @submit.prevent="update">

                <div>
                    <InputLabel label="Nombre" value="Nombre del departamento" />
                    <TextInput v-model="department.name" />
                    <div v-if="$page.props.errors.updateDepartment" class="text-dynacom-red text-sm">
                        {{ $page.props.errors.updateDepartment.name }}
                    </div>
                </div>

                <div>
                    <InputLabel label="Descripción" value="Descripción del departamento" />
                    <TextInput v-model="department.description" />
                    <div v-if="$page.props.errors.updateDepartment" class="text-dynacom-red text-sm">
                        {{ $page.props.errors.updateDepartment.description }}
                    </div>
                </div>

                <div v-if="$page.props.flash.departmentUpdated" class="alert">
                    <p class="flashMsg !m-0">
                    {{ $page.props.flash.departmentUpdated }}
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
    name: "DepartmentDetails",
    components: {
        PrimaryButton,
        InputLabel,
        TextInput,
        InputError,
    },
    props: {
        department: {
            type: Object,
            required: true,
        }
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
            let route = "/dashboard/config/departments/" + t.department.id;

            t.submiting = true;

            t.$inertia.put(route, t.department, {
                preserveScroll: true,
                errorBag: "updateDepartment",
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
