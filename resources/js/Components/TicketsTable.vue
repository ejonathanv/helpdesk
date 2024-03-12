<template>
    <!-- Encabezado de la tabla -->
    <div class="flex items-center mb-7">
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Lista de tickets
                <span v-if="archived">archivados</span>
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                Los tickets mas recientes aparecen primero.
            </p>
        </header>
        <div class="ml-auto" v-if="type === 'internal'">
            <div class="ml-auto space-x-4 flex items-stretch" v-if="!archived">
                <!-- Busqueda de tickets -->
                <form :action="searchRoute()" method="GET"
                    class="flex items-center space-x-3 relative">
                    <input type="text" 
                        class="form-control"
                        name="search" 
                        placeholder="Buscar ticket..." />
                    <button class="absolute right-3 top-2 m-0 z-10 cursor-pointer"
                        type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <!-- Botón para crear nuevo ticket -->
                <PrimaryLink :href="route('tickets.create')" v-if="$page.props.auth.user" class="space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Nuevo ticket</span>
                </PrimaryLink>
                <PrimaryLink :href="route('guest.new-ticket')" v-else class="space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Nuevo ticket</span>
                </PrimaryLink>
                <!-- Filtrar tickets -->
                <SecondaryButton href="#" 
                    class="space-x-2" 
                    @click.prevent="initFilter">
                    <i class="fas fa-filter"></i>
                    <span>Filtrar</span>
                </SecondaryButton>
            </div>
            <div class="ml-auto space-x-4 flex items-stretch" v-else>
                <!-- Busqueda de tickets -->
                <form :action="searchRoute()" method="GET"
                    class="flex items-center space-x-3 relative">
                    <input type="text" 
                        class="form-control"
                        name="search" 
                        placeholder="Buscar ticket..." />
                    <button class="absolute right-3 top-2 m-0 z-10 cursor-pointer"
                        type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <!-- Filtrar tickets -->
                <SecondaryButton href="#" 
                    class="space-x-2" 
                    @click.prevent="filter = !filter">
                    <i class="fas fa-filter"></i>
                    <span>Filtrar</span>
                </SecondaryButton>
            </div>
        </div>
    </div>
    <!-- Alerta de ticket archivado o eliminado -->
    <div v-if="$page.props.flash.ticketArchived" class="alert">
        <p class="flashMsg">
            {{ $page.props.flash.ticketArchived }}
        </p>
    </div>

    <!-- Filtrador de tickets -->
    <div v-if="filter" class="py-7 bg-gray-100 mb-7 -mx-8 px-8">
        <div class="w-5/12">
            <form action="" 
                class="flex flex-col space-y-2" 
                @submit.prevent="filterTickets">
                <div v-if="loadingAccounts" class="mb-4">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>
                        Cargando cuentas...
                    </span>
                </div>
                <div class="flex-1" v-else>
                    <InputLabel label="Cuenta" value="Cuenta" />
                    <select
                        id="account"
                        class="form-control"
                        v-model="filterData.account"
                    >
                        <option selected disabled value="">
                            Cuenta
                        </option>
                        <option
                            v-for="(account, index) in accounts"
                            :key="index"
                            :value="account.name"
                        >
                            {{ account.name }}
                        </option>
                    </select>
                </div>
                <div class="flex-1">
                    <InputLabel label="Departamento" value="Departamento" />
                    <select
                        id="department"
                        class="form-control"
                        v-model="filterData.department"
                    >
                        <option selected disabled value="">
                            Departamento
                        </option>
                        <option
                            v-for="(department, index) in departments"
                            :key="index"
                            :value="department.id"
                        >
                            {{ department.name }}
                        </option>
                    </select>
                </div>
                <div class="flex-1">
                    <InputLabel label="Estado" value="Etapa" />
                    <select
                        id="status"
                        class="form-control"
                        v-model="filterData.status"
                    >
                        <option selected disabled value="">
                            Etapa
                        </option>
                        <option
                            v-for="(status, index) in statuses"
                            :key="index"
                            :value="status.id"
                        >
                            {{ status.name }}
                        </option>
                    </select>
                </div>
                <div class="flex-1">
                    <InputLabel label="Severidad" value="Severidad" />
                    <select
                        id="severity"
                        class="form-control"
                        v-model="filterData.severity"
                    >
                        <option selected disabled value="">
                            Severidad
                        </option>
                        <option
                            v-for="(severity, index) in severities"
                            :key="index"
                            :value="severity.id"
                        >
                            {{ severity.name }}
                        </option>
                    </select>
                </div>
                <div class="flex-1">
                    <InputLabel label="Prioridad" value="Prioridad" />
                    <select
                        id="priority"
                        class="form-control"
                        v-model="filterData.priority"
                    >
                        <option selected disabled value="">
                            Prioridad
                        </option>
                        <option
                            v-for="(priority, index) in priorities"
                            :key="index"
                            :value="priority.id"
                        >
                            {{ priority.name }}
                        </option>
                    </select>    
                </div>
                <div class="flex-1">
                    <InputLabel label="Categoría" value="Categoría" />
                    <select
                        id="category"
                        class="form-control"
                        v-model="filterData.category"
                    >
                        <option selected disabled value="">
                            Categoria
                        </option>
                        <option
                            v-for="(category, index) in categories"
                            :key="index"
                            :value="category.id"
                        >
                            {{ category.full_name }}
                        </option>
                    </select>
                </div>
                <div class="flex-1">
                    <InputLabel label="Ingeniero" value="Ingeniero" />
                    <select
                        id="agent"
                        class="form-control"
                        v-model="filterData.agent"
                    >
                        <option selected disabled value="">
                            Ingeniero
                        </option>
                        <option
                            v-for="(agent, index) in agents"
                            :key="index"
                            :value="agent.id"
                        >
                            {{ agent.name }}
                        </option>
                    </select>
                </div>
                <div class="pt-5 flex items-center space-x-4">
                    <PrimaryButton type="submit">
                        <span v-if="submiting" class="flex items-center space-x-2">
                            <i class="fas fa-spinner fa-spin"></i>
                            <span> Un momento... </span>
                        </span>
                        <span v-else>Filtrar</span>
                    </PrimaryButton>
                    <Link :href="route('tickets.index')">
                        <span>
                            Limpiar filtros
                        </span>
                    </Link>
                </div>
            </form>
        </div>
    </div>

    <!-- Si hay tickets registrados, se muestra la tabla de tickets -->
    <div class="table-responsive" v-if="tickets.data.length">
        <table class="table bordered">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Asunto</th>
                    <th>Cuenta</th>
                    <th>Ingeniero</th>
                    <th v-if="$page.props.auth.user">
                        Departamento
                    </th>
                    <th>Etapa</th>
                    <th>Fecha</th>
                    <th class="text-right" v-if="!archived">
                        Detalles
                    </th>
                    <th v-else>
                        Restaurar
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="ticket in tickets.data" :key="ticket.id">
                    <td>{{ ticket.number }}</td>
                    <td>{{ ticket.subject }}</td>
                    <td>{{ ticket.account_name }}</td>
                    <td>{{ ticket.agent_name }}</td>
                    <td v-if="$page.props.auth.user">
                        {{ ticket.department_name }}
                    </td>
                    <td class="whitespace-nowrap" v-html="ticket.status_badge"></td>
                    <td class="whitespace-nowrap">{{ ticket.created_at }}</td>
                    <td class="text-right" v-if="!archived">
                        <Link :href="route('tickets.show', ticket.id)" v-if="type === 'internal'">
                            Ver más
                        </Link>
                        <Link :href="route('guest.ticket', ticket.id)" v-else>
                            Ver más
                        </Link>
                    </td>
                    <td v-else class="text-center">
                        <Link href="#" @click.prevent="restoreTicket(ticket.id)" class="space-x-2 text-dynacom-red">
                            <span>Restaurar</span>
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="mt-5">
            <Pagination :links="tickets.links" />
        </div>
    </div>
    <!-- Si no hay tickets registrados, se muestra un mensaje -->
    <div v-else>
        <p class="text-gray-500 text-center my-6">
            No hay tickets registrados.
        </p>
    </div>
</template>
<script>
import Pagination from "@/Components/Pagination.vue";
import PrimaryLink from "@/Components/PrimaryLink.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Link } from "@inertiajs/vue3";
export default {
    name: "TicketsTable",
    components: {
        PrimaryLink,
        PrimaryButton,
        InputLabel,
        SecondaryButton,
        Link,
        Pagination,
    },
    props: {
        type: {
            type: String,
            default: "internal"
        },
        archived: {
            type: Boolean,
            default: false,
        },
        tickets: {
            type: Array,
            required: true,
        },
        departments: {
            type: Array,
            required: true,
        },
        agents: {
            type: Array,
            required: true,
        },
        statuses: {
            type: Array,
            required: true,
        },
        severities: {
            type: Array,
            required: true,
        },
        priorities: {
            type: Array,
            required: true,
        },
        categories: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            loadingAccounts: true,
            accounts: [],
            filter: false,
            filterData: {
                filterdata: true,
                account: "",
                department: "",
                status: "",
                priority: "",
                severity: "",
                category: "",
                agent: "",
            },
        };
    },
    methods: {
        searchRoute() {
            const t = this;
            let internalRoute = t.archived ? "/dashboard/tickets-archived" : "/dashboard/tickets";
            let guestRoute = "/guest/tickets";
            return t.type === "internal" ? internalRoute : guestRoute;
        },
        initFilter(){
            const t = this;
            t.getAccountsList();
            t.filter = true;
        },
        filterTickets(){
            const t = this;
            let internalRoute = t.archived ? "/dashboard/tickets-archived" : "/dashboard/tickets";
            let guestRoute = "/guest/tickets";
            let route = t.type === "internal" ? internalRoute : guestRoute;
            t.$inertia.get(route, t.filterData, {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => {
                    t.filter = false;
                },
            });
        },
        getAccountsList(){
            const t = this;
            let route = '/dashboard/accounts-list';
            fetch(route)
                .then(response => response.json())
                .then(data => {
                    t.accounts = data;
                    t.loadingAccounts = false;
                });
        },
        restoreTicket(id){
            const t = this;
            let confirm = window.confirm("¿Estás seguro de que deseas restaurar este ticket?");
            let route = `/dashboard/restore-ticket/${id}`;

            if(confirm){
                t.$inertia.post(route, {}, {
                    preserveState: true,
                    preserveScroll: true,
                    onSuccess: () => {
                        t.filter = false;
                    },
                });
            }

        }
    }
};
</script>
