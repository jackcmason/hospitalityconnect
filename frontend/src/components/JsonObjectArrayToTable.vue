<template>
  <div v-if="items.length === 0">
    <p>No Results</p>
  </div>
  <div v-else>
    <table>
      <thead>
        <tr>
          <th v-for="header in headers" :key="header">{{ header }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="item in items" :key="item.id">
          <td v-for="(value, key) in item" :key="key">{{ value }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    items: {
      type: Array,
      required: true,
    },
  },
  computed: {
    headers() {
      // Extracting unique keys from all items
      let headers = [];
      this.items.forEach((item) => {
        Object.keys(item).forEach((key) => {
          if (!headers.includes(key)) {
            headers.push(key);
          }
        });
      });
      return headers;
    },
  },
};
</script>

<style scoped>
table {
  border-collapse: collapse;
  width: 100%;
}

th,
td {
  border: 1px solid #999999;
  text-align: left;
  padding: 8px;
}

th {
  background-color: #dddddd;
}

tr:nth-child(even) {
  background-color: #fafafa;
}

tr:nth-child(odd) {
  background-color: #ffffff;
}

tr:hover {
  background-color: #f2f2f2;
}
</style>
