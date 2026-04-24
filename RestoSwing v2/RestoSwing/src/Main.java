import javax.swing.*;

public static void main(String[] args) {
    SwingUtilities.invokeLater(() ->
    {
        new Commande_liste().setVisible(true);
    });
}
