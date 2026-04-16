import java.net.URLEncoder;
import java.nio.charset.StandardCharsets;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import javax.swing.JOptionPane;

public class APIService {

    private final String baseApi;

    public APIService() {
        this.baseApi = resolveBaseApi();
    }

    private static final String CMD_ATTENTE = "/commandes_en_attente.php";
    private static final String BASE_API = "http://localhost/www/APPRESTO/AP-SLAM-SIO2/Appresto/api";

    /* ===================== COMMANDES ===================== */

    public List<Commande> getToutesLesCommandes() {
        NetworkUtils.HttpResult response = NetworkUtils.get(baseApi + CMD_ATTENTE);
        Map<String, Object> root = getJson(response);
        ensureSuccess(response, root, "Impossible de charger les commandes");

        List<Commande> result = mapCommandes(root);

        // Compatibilité: certaines versions du endpoint renvoient seulement un état (souvent id_etat=4).
        // Si on détecte un filtre actif, on agrège les commandes par états connus.
        if (hasServerSideEtatFilter(root)) {
            Map<Integer, Commande> merged = new java.util.LinkedHashMap<>();

            for (Commande c : result) {
                merged.put(c.getId(), c);
            }

            for (int etat = 1; etat <= 10; etat++) {
                List<Commande> batch = getCommandesParEtat(etat);
                for (Commande c : batch) {
                    merged.put(c.getId(), c);
                }
            }

            result = new ArrayList<>(merged.values());
        }

        return result;
    }

    public List<Commande> getCommandesEnAttente() {
        return getToutesLesCommandes();
    }

    public List<Ligne> getDetailsCommande(int idCommande) {
        return buildFallbackDetailsFromCommandes(idCommande);
    }

    private List<Ligne> buildFallbackDetailsFromCommandes(int idCommande) {
        List<Ligne> fallback = new ArrayList<>();

        try {
            for (Commande c : getToutesLesCommandes()) {
                if (c.getId() != idCommande) {
                    continue;
                }

                Ligne synthetic = new Ligne();
                synthetic.setIdCommande(idCommande);
                synthetic.setIdProduit(0);

                String label = toString(c.getLibelle()).trim();
                if (label.isEmpty()) {
                    label = "Commande #" + idCommande;
                }

                synthetic.setPlat(label);
                synthetic.setQuantite(1);
                synthetic.setPrix(c.getTotalTtc());

                fallback.add(synthetic);
                break;
            }
        } catch (Exception ignored) {
            // L'UI gère déjà une liste vide: on garde un fallback silencieux.
        }

        return fallback;
    }

    /* ===================== ACTIONS ===================== */

    public String accepterCommande(int idCommande) {
        return updateEtatCommande("commande_accepter.php", idCommande);
    }

    public String refuserCommande(int idCommande) {
        return updateEtatCommande("commande_refuser.php", idCommande);
    }

    public String terminerCommande(int idCommande) {
        return updateEtatCommande("commande_terminer.php", idCommande);
    }

    private String updateEtatCommande(String endpoint, int idCommande) {
        Map<String, String> form = new HashMap<>();
        form.put("id_commande", String.valueOf(idCommande));

        String endpointUrl = baseApi + "/" + endpoint;
        NetworkUtils.HttpResult response = NetworkUtils.postForm(endpointUrl, form);

        // Compatibilité: certains scripts PHP rejettent le POST form (HTTP 400) et n'acceptent que GET/JSON.
        if (response.getStatusCode() == 400) {
            String getUrl = endpointUrl + "?id_commande=" +
                    URLEncoder.encode(String.valueOf(idCommande), StandardCharsets.UTF_8);
            response = NetworkUtils.get(getUrl);
        }

        Map<String, Object> root = getJson(response);
        ensureSuccess(response, root, "Action impossible sur la commande");

        return toString(root.get("message"));
    }

    /* ===================== JSON ===================== */

    private Map<String, Object> getJson(NetworkUtils.HttpResult response) {
        String body = response.getBody();

        if (body == null || body.trim().isEmpty()) {
            throw new RuntimeException("Réponse vide du serveur");
        }

        String json = extractJsonBody(body);

        try {
            return asObject(SimpleJson.parse(json));
        } catch (Exception e) {
            throw new RuntimeException("JSON invalide: " + json);
        }
    }

    private String extractJsonBody(String body) {
        // Nettoie un éventuel BOM UTF-8 au début de la réponse.
        String cleaned = body;
        if (!cleaned.isEmpty() && cleaned.charAt(0) == '\uFEFF') {
            cleaned = cleaned.substring(1);
        }

        String trimmed = cleaned.trim();
        if (trimmed.startsWith("{") || trimmed.startsWith("[")) {
            return trimmed;
        }

        // Tolère les warnings/notices PHP entourant la réponse JSON.
        int objectStart = trimmed.indexOf('{');
        int objectEnd = trimmed.lastIndexOf('}');
        if (objectStart >= 0 && objectEnd > objectStart) {
            return trimmed.substring(objectStart, objectEnd + 1);
        }

        int arrayStart = trimmed.indexOf('[');
        int arrayEnd = trimmed.lastIndexOf(']');
        if (arrayStart >= 0 && arrayEnd > arrayStart) {
            return trimmed.substring(arrayStart, arrayEnd + 1);
        }

        throw new RuntimeException("Réponse non JSON: " + body);
    }

    private void ensureSuccess(NetworkUtils.HttpResult response,
                               Map<String, Object> root,
                               String fallbackMessage) {

        boolean success = isSuccess(root.get("success"));

        if (response.getStatusCode() == 0 || !response.isSuccess() || !success) {
            String msg = toString(root.get("message"));
            if (msg.isEmpty()) msg = fallbackMessage;

            throw new RuntimeException(msg + " (HTTP " + response.getStatusCode() + ")");
        }
    }

    private boolean isSuccess(Object value) {
        if (value instanceof Boolean) return (Boolean) value;
        if (value instanceof String) return "true".equalsIgnoreCase((String) value);
        return false;
    }

    /* ===================== UTILS ===================== */

    @SuppressWarnings("unchecked")
    private Map<String, Object> asObject(Object value) {
        if (value instanceof Map) return (Map<String, Object>) value;
        throw new RuntimeException("JSON inattendu (objet attendu)");
    }

    @SuppressWarnings("unchecked")
    private List<Object> asArray(Object value) {
        if (value instanceof List) return (List<Object>) value;
        return new ArrayList<>();
    }

    private int toInt(Object value) {
        try {
            if (value == null) return 0;
            if (value instanceof Number) return ((Number) value).intValue();
            String str = String.valueOf(value).trim();
            if (str.isEmpty() || "null".equalsIgnoreCase(str)) return 0;
            return Integer.parseInt(str);
        } catch (Exception e) {
            throw new RuntimeException("Erreur conversion int: " + value);
        }
    }

    private double toDouble(Object value) {
        try {
            if (value == null) return 0.0;
            if (value instanceof Number) return ((Number) value).doubleValue();
            String str = String.valueOf(value).trim();
            if (str.isEmpty() || "null".equalsIgnoreCase(str)) return 0.0;

            // Accepte les formats fréquents côté PHP/BDD: "1 234,56", "1,234.56", "12.50 €".
            str = str.replace('\u00A0', ' ');
            str = str.replace("€", "").replaceAll("\\s+", "");

            int lastComma = str.lastIndexOf(',');
            int lastDot = str.lastIndexOf('.');

            if (lastComma >= 0 && lastDot >= 0) {
                // Si la virgule est le séparateur décimal (ex: 1.234,56), on supprime les points.
                if (lastComma > lastDot) {
                    str = str.replace(".", "");
                    str = str.replace(',', '.');
                } else {
                    // Sinon le point est décimal (ex: 1,234.56), on supprime les virgules.
                    str = str.replace(",", "");
                }
            } else {
                str = str.replace(',', '.');
            }

            str = str.replaceAll("[^0-9eE+\\-\\.]", "");

            if (str.isEmpty() || ".".equals(str) || "-".equals(str) || "+".equals(str)) {
                return 0.0;
            }

            return Double.parseDouble(str);
        } catch (Exception e) {
            return 0.0;
        }
    }

    private String toString(Object value) {
        return value == null ? "" : String.valueOf(value);
    }

    private List<Commande> mapCommandes(Map<String, Object> root) {
        List<Commande> result = new ArrayList<>();

        for (Object rowObj : asArray(root.get("data"))) {
            Map<String, Object> row = asObject(rowObj);

            Commande c = new Commande();
            c.setId(toInt(valueFrom(row, "id_commande")));
            c.setLibelle(toString(row.get("lib_commande")));
            c.setTypeCommande(toString(row.get("type_commande")));
            c.setDateCommande(toString(row.get("date_commande")));
            c.setTotalTtc(toDouble(valueFrom(row, "total_TTC", "total_ttc", "totalTTC")));
            c.setIdEtat(toInt(valueFrom(row, "id_etat")));
            c.setStatut(toString(row.get("lib_etat")));
            result.add(c);
        }

        return result;
    }

    private boolean hasServerSideEtatFilter(Map<String, Object> root) {
        if (!root.containsKey("id_etat")) {
            return false;
        }
        Object value = root.get("id_etat");
        if (value == null) {
            return false;
        }
        String text = String.valueOf(value).trim();
        return !text.isEmpty() && !"null".equalsIgnoreCase(text);
    }

    private List<Commande> getCommandesParEtat(int idEtat) {
        String url = baseApi + CMD_ATTENTE + "?id_etat=" +
                URLEncoder.encode(String.valueOf(idEtat), StandardCharsets.UTF_8);

        NetworkUtils.HttpResult response = NetworkUtils.get(url);
        Map<String, Object> root = getJson(response);

        if (response.getStatusCode() == 0 || !response.isSuccess() || !isSuccess(root.get("success"))) {
            return new ArrayList<>();
        }

        return mapCommandes(root);
    }

    private Object valueFrom(Map<String, Object> row, String... keys) {
        for (String key : keys) {
            if (row.containsKey(key)) {
                return row.get(key);
            }
        }
        return null;
    }

    private String resolveBaseApi() {
        String fromProperty = System.getProperty("resto.api.base");
        if (isUsableBase(fromProperty)) {
            return trimTrailingSlash(fromProperty);
        }

        String fromEnv = System.getenv("RESTO_API_BASE");
        if (isUsableBase(fromEnv)) {
            return trimTrailingSlash(fromEnv);
        }

        String[] candidates = new String[] {
            BASE_API,
                "http://localhost/www/appresto/Appresto/api",
        };

        for (String candidate : candidates) {
            if (isUsableBase(candidate)) {
                return trimTrailingSlash(candidate);
            }
        }

        String manualBase = askBaseApiToUser();
        if (manualBase != null && isUsableBase(manualBase)) {
            return trimTrailingSlash(manualBase);
        }

        throw new RuntimeException(
                "API introuvable. Verifie l'URL API (ex: http://localhost/www/APPRESTO/Appresto/api)."
        );
    }

    private String askBaseApiToUser() {
        String defaultValue = "http://localhost/www/APPRESTO/Appresto/api";
        String input = JOptionPane.showInputDialog(
                null,
                "URL de base API introuvable automatiquement.\nSaisis l'URL de ton API :",
                defaultValue
        );

        if (input == null) {
            return null;
        }

        String trimmed = input.trim();
        if (trimmed.isEmpty()) {
            return null;
        }

        return trimmed;
    }

    private boolean isUsableBase(String base) {
        if (base == null || base.trim().isEmpty()) {
            return false;
        }

        String baseUrl = trimTrailingSlash(base.trim());
        String probeUrl = baseUrl + CMD_ATTENTE;
        NetworkUtils.HttpResult response = NetworkUtils.get(probeUrl);

        if (!response.isSuccess()) {
            return false;
        }

        String body = response.getBody();
        if (body == null) {
            return false;
        }

        String lowered = body.toLowerCase();
        return lowered.contains("success") && lowered.contains("data");
    }

    private String trimTrailingSlash(String value) {
        if (value.endsWith("/")) {
            return value.substring(0, value.length() - 1);
        }
        return value;
    }
}