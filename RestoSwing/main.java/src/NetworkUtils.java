import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.URLEncoder;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;
import java.util.Map;

public class NetworkUtils {

    private static final int CONNECT_TIMEOUT_MS = 7000;
    private static final int READ_TIMEOUT_MS = 10000;

    public static class HttpResult {
        private final int statusCode;
        private final String body;

        public HttpResult(int statusCode, String body) {
            this.statusCode = statusCode;
            this.body = body;
        }

        public int getStatusCode() {
            return statusCode;
        }

        public String getBody() {
            return body;
        }

        public boolean isSuccess() {
            return statusCode >= 200 && statusCode < 300;
        }
    }

    public static HttpResult get(String urlStr) {
        try {
            URL url = new URL(urlStr);
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            conn.setRequestMethod("GET");
            conn.setConnectTimeout(CONNECT_TIMEOUT_MS);
            conn.setReadTimeout(READ_TIMEOUT_MS);

            int status = conn.getResponseCode();
            String body = readResponse(conn, status);
            return new HttpResult(status, body);

        } catch (Exception e) {
            return new HttpResult(0, "{\"success\":false,\"message\":\"Erreur reseau GET: " + escapeForJson(e.getMessage()) + "\"}");
        }
    }

    public static HttpResult postForm(String urlStr, Map<String, String> formFields) {
        try {
            URL url = new URL(urlStr);
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            conn.setRequestMethod("POST");
            conn.setConnectTimeout(CONNECT_TIMEOUT_MS);
            conn.setReadTimeout(READ_TIMEOUT_MS);
            conn.setDoOutput(true);
            conn.setRequestProperty("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");

            String encodedForm = encodeForm(formFields);
            try (OutputStream os = conn.getOutputStream()) {
                os.write(encodedForm.getBytes(StandardCharsets.UTF_8));
            }

            int status = conn.getResponseCode();
            String body = readResponse(conn, status);
            return new HttpResult(status, body);

        } catch (Exception e) {
            return new HttpResult(0, "{\"success\":false,\"message\":\"Erreur reseau POST: " + escapeForJson(e.getMessage()) + "\"}");
        }
    }

    private static String readResponse(HttpURLConnection conn, int status) throws IOException {
        StringBuilder result = new StringBuilder();
        try (BufferedReader reader = new BufferedReader(
            new InputStreamReader(
                status >= 200 && status < 400 ? conn.getInputStream() : conn.getErrorStream(),
                StandardCharsets.UTF_8
            )
        )) {
            String line;
            while ((line = reader.readLine()) != null) {
            result.append(line);
            }
        }

        return result.toString();
    }

    private static String encodeForm(Map<String, String> formFields) {
        StringBuilder sb = new StringBuilder();
        boolean first = true;
        for (Map.Entry<String, String> entry : formFields.entrySet()) {
            if (!first) {
                sb.append("&");
            }
            sb.append(URLEncoder.encode(entry.getKey(), StandardCharsets.UTF_8));
            sb.append("=");
            sb.append(URLEncoder.encode(entry.getValue(), StandardCharsets.UTF_8));
            first = false;
        }
        return sb.toString();
    }

    private static String escapeForJson(String value) {
        if (value == null) {
            return "";
        }
        return value.replace("\\", "\\\\").replace("\"", "\\\"");
    }
}