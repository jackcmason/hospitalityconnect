<template>
  <div style="width: 400px; margin: auto; text-align: center">
    <main>
      <h1>Hospitality Connect</h1>
      <p>
        Uploading .csv data? Click <router-link to="/upload">here</router-link>
      </p>
    </main>
    <body>
      <div class="container">
        <form @submit.prevent="handleSubmit" autocomplete="off">
          <div class="form-group">
            <label for="latitude">Latitude:</label>
            <input
              type="text"
              id="latitude"
              name="latitude"
              placeholder="latitude"
              v-model="formData.latitude"
            />
          </div>
          <div class="form-group">
            <label for="longitude">Longitude:</label>
            <input
              type="text"
              id="longitude"
              name="longitude"
              placeholder="longitude"
              v-model="formData.longitude"
            />
          </div>
          <div class="form-group">
            <label for="radius">Search Radius (km):</label>
            <input
              type="number"
              id="radius"
              name="radius"
              min="0"
              placeholder="(in kilometers):"
              v-model="formData.km"
            />
          </div>
          <input type="submit" value="Search" />
        </form>
      </div>

      <div>
        <Transition>
          <h2 v-if="resultList !== null">Results</h2>
        </Transition>
        <div>
          <Transition>
            <json-object-array-to-table
              :items="resultList"
              v-if="resultList !== null"
            />
          </Transition>
        </div>
      </div>
    </body>
  </div>
</template>

<script>
import JsonObjectArrayToTable from "@/components/JsonObjectArrayToTable.vue";

export default {
  components: {
    JsonObjectArrayToTable,
  },
  data() {
    return {
      formData: {
        latitude: "",
        longitude: "",
        km: 0,
      },
      resultList: null,
    };
  },
  methods: {
    async handleSubmit() {
      let latitudeAndLongitudeRegexPattern = /^-?[0-9]{1,3}(?:\.[0-9]{1,10})?$/;

      if (!latitudeAndLongitudeRegexPattern.test(this.formData.latitude)) {
        // Invalid Latitude"
        this.resultList = [];
      } else if (
        !latitudeAndLongitudeRegexPattern.test(this.formData.longitude)
      ) {
        // Invalid Longitude
        this.resultList = [];
      } else {

        const lat = this.formData.latitude;
        const lon = this.formData.longitude;
        const rad = this.formData.km;

        const API_BASE_URL = "http://localhost:8000";
        const apiUrl = `${API_BASE_URL}/api/students/nearby?latitude=${lat}&longitude=${lon}&radius=${rad}`;

        const response = (await fetch(apiUrl))
          .json()
          .then((data) => (this.resultList = data));
      }
      this.resetForm();
    },

    resetForm() {
      this.formData.latitude = "";
      this.formData.longitude = "";
      this.formData.km = 0;
    },
  },
};
</script>

<style>
body {
  font-family: Arial, sans-serif;
  background-color: #f5f5f5;
  margin: 0;
  padding: 0;
}

form {
  max-width: 400px;
  padding: 20px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
  font-size: 36px;
  color: #333;
  margin-bottom: 10px;
}

h2 {
  font-size: 24px;
  color: #666;
  margin-bottom: 20px;
}

label {
  font-size: 18px;
  color: #333;
  display: block;
  margin-bottom: 8px;
}

input[type="text"],
input[type="email"],
input[type="number"],
textarea {
  width: 100%;
  padding: 10px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
  margin-bottom: 10px;
}

textarea {
  height: 120px;
}

button,
input[type="submit"] {
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 12px 20px;
  font-size: 18px;
  border-radius: 5px;
  cursor: pointer;
}

input[type="submit"]:hover {
  background-color: #0056b3;
}

.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
