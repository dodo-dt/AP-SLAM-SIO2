import javax.swing.SwingUtilities;

public class Restoswing {
    public static void main(String[] args) {
        SwingUtilities.invokeLater(() -> {
            new Commande_liste().setVisible(true);
        });
    }
}