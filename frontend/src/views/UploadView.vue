<template>
  <div>
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Upload CSV File</title>
    </head>
    <body>
      <div style="width: 400px; margin: auto; text-align: center">
        <h1>Upload CSV File</h1>
        <p>Wanting to search? Click <router-link to="/">here</router-link></p>
        <form
          :action="apiUrl"
          method="post"
          enctype="multipart/form-data"
          target="postFrame"
        >
          <input type="file" name="file" @change="fileUpload" />
          <!--<input type="submit" value="Upload">-->
          <button type="submit" :disabled="uploadDisabled" @click="changeText">{{ buttonText }}</button>
        </form>
      </div>
    </body>
  </div>
  <iframe id="postFrame" name="postFrame" style="display: none"></iframe>
</template>

<script>
const API_BASE_URL = "http://localhost:8000";

export default {
  data() {
    return {
      apiUrl: `${API_BASE_URL}/api/students/`,
      buttonText: "Upload",
      uploadDisabled: true
    };
  },
  methods: {
    changeText() {
      this.buttonText = "Done";
    },
    fileUpload() {
      this.uploadDisabled = false;
    }
  },
};
</script>
