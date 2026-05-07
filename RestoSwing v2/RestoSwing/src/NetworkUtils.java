import org.json.*;

import javax.net.ssl.SSLSession;
import java.lang.reflect.Array;
import java.net.URI;
import java.net.URISyntaxException;
import java.net.URLEncoder;
import java.net.http.HttpClient;
import java.net.http.HttpHeaders;
import java.net.http.HttpRequest;
import java.net.http.HttpResponse;
import java.nio.charset.StandardCharsets;
import java.util.*;

public class NetworkUtils {

    String json = "";
    static String url = "http://localhost/www/APPRESTO/AP-SLAM-SIO2/Appresto/api/";

    static HttpClient client = HttpClient.newHttpClient();

    //  ----------API REST----------

    public NetworkUtils() {
        try {

            HttpRequest request = HttpRequest.newBuilder()
                    .uri(new URI(url))
                    .build();
            HttpResponse<String> response = client.send(request,
                    HttpResponse.BodyHandlers.ofString());
            if (response.statusCode() == 200) {
                json = response.body();
            } else {
                System.err.println("Erreur : Code statut " + response.statusCode());
            }
        } catch (Exception ex) {
            System.err.println("Erreur : " + ex.getMessage());
        }
    }


    // ----------ACTIONS----------


    public static String accepterCommande(int idCommande) {
        return updateEtatCommande(idCommande, "commande_accepter.php");
    }

    public static String refuserCommande(int idCommande) {
        return updateEtatCommande(idCommande, "commande_refuser.php");
    }

    public static String terminerCommande(int idCommande) {
        return updateEtatCommande(idCommande,"commande_terminer.php");
    }

    private static String updateEtatCommande(int idCommande, String fichier) {

        try {
            HttpRequest request = HttpRequest.newBuilder()
                    .uri(new URI(url + fichier + "?id_commande=" + idCommande))
                    .GET()
                    .build();

            HttpResponse<String> response = client.send(request,
                    HttpResponse.BodyHandlers.ofString());

            System.out.println("URL : " + url + fichier + "?id_commande=" + idCommande);
            System.out.println("CODE : " + response.statusCode());
            System.out.println("BODY : " + response.body());

            if (response.statusCode() == 200) {
                return response.body();
            }

            return "Erreur HTTP " + response.statusCode();

        } catch (Exception e) {
            return "Erreur : " + e.getMessage();
        }
    }

    // ---------COMMANDES----------

    public static ArrayList<Commande> getCommandesEnAttente() {
        return getToutesLesCommandes();
    }

    public static ArrayList<Ligne> getDetailsCommande(int idCommande) {

        ArrayList<Ligne> lignes = new ArrayList<>();

        HttpResponse<String> response =
                get(url + "commandes_en_attente.php?action=details&id_commande=" + idCommande);

        if (response == null || response.statusCode() != 200) {
            System.out.println("Erreur details commande");
            return lignes;
        }

        try {
            JSONObject json = new JSONObject(response.body());
            JSONArray data = json.getJSONArray("data");

            for (int i = 0; i < data.length(); i++) {

                JSONObject obj = data.getJSONObject(i);

                Ligne l = new Ligne();

                l.setId_commande(idCommande);
                l.setId_produit(obj.getInt("id_produit"));
                l.setProduit(obj.getString("lib_produit"));
                l.setQuantite(obj.getInt("quantite"));
                l.setMontant_unitaire_ht(obj.getDouble("montant_unitaire_HT"));

                lignes.add(l);
            }

        } catch (Exception e) {
            System.out.println("Erreur parsing details : " + e.getMessage());
        }

        return lignes;
    }

    // --------------- GESTION D'ERREUR-----------------
    public static HttpResponse<String> get(String url) {
        try {
            System.out.println("------------------------------");
            System.out.println("URL appelée : " + url);

            HttpRequest request = HttpRequest.newBuilder()
                    .uri(new URI(url))
                    .GET()
                    .build();

            HttpResponse<String> response = client.send(
                    request,
                    HttpResponse.BodyHandlers.ofString()
            );

            System.out.println("CODE HTTP : " + response.statusCode());
            System.out.println("BODY : " + response.body());
            System.out.println("------------------------------");

            return response;

        } catch (Exception e) {
            System.out.println("GET ERROR : " + e.getMessage());
            return null;
        }
    }

    public static ArrayList<Commande> getToutesLesCommandes() {

        HttpResponse<String> response =
                NetworkUtils.get(url + "commandes_en_attente.php");

        ArrayList<Commande> commandes = new ArrayList<>();

        if (response == null || response.statusCode() != 200) {
            System.out.println("Erreur lors du chargement");
            return commandes;
        }

        try {
            JSONObject json = new JSONObject(response.body());

            JSONArray data = json.getJSONArray("data");

            for (int i = 0; i < data.length(); i++) {

                JSONObject obj = data.getJSONObject(i);

                Commande c = new Commande();

                c.setId_commande(obj.getInt("id_commande"));
                c.setLib_commande(obj.getString("lib_commande"));
                c.setType_commande(obj.getString("type_commande"));
                c.setDate_commande(obj.getString("date_commande"));

                // total_TTC est souvent string → conversion simple
                c.setTotal_TTC(Double.parseDouble(obj.getString("total_TTC")));

                c.setStatut(obj.getString("lib_etat"));

                ArrayList<Ligne> lignes = getDetailsCommande(c.getId_commande());
                c.setLignes(lignes);

                commandes.add(c);
            }

        } catch (Exception e) {
            System.out.println("Erreur parsing : " + e.getMessage());
        }

        return commandes;
    }

}
