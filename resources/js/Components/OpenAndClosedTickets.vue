<template>
    <div class="card">
        <table class="table">
            <thead>
                <tr>
                    <th>Abiertos</th>
                    <th>Cerrados</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <h2>
                            {{ openTickets }} 
                        </h2>
                        Tickets
                    </td>
                    <td>{{ closedTickets }} Tickets</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>

export default {
    name: "OpenAndClosedTickets",
    props: {
        openTickets: {
            type: Array,
            required: true,
            default: 24,
        },
        closedTickets: {
            type: Array,
            required: true,
            default: 36,
        },
    },
    data() {
        return {};
    },
}

</script>