<template>
    <v-data-table
            :items="items"
            class="elevation-1"
            hide-actions
            :headers="headers"
          >
            <template v-slot:items="props">
              <td>{{ props.item.name }}</td>
              <td class="text-xs-right">{{ props.item.calories }}</td>
              <td class="text-xs-right">{{ props.item.fat }}</td>
              <td class="text-xs-right">{{ props.item.carbs }}</td>
              <td class="text-xs-right">{{ props.item.protein }}</td>
              <td class="text-xs-right">{{ props.item.iron }}</td>
            </template>
    </v-data-table>
</template>

<script>
export default {
  name: "ExampleIndex",
  props: {
      source: String
    },
  data() {
    return {
        loading: false,
        items: [],
        headers: [
            {text: 'Periodo',value: '',sort: false},
            {text: 'clase', value: '', sort: false}
        ]
    }
  },

  created() {
    let self = this
  },

  methods: {
  },

  computed: {
    },
};
</script>