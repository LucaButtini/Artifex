<?php
namespace App\Controller;

class AdminScheduleController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Mostra tutte le programmazioni (eventi_visite)
    public function index()
    {
        $sql = "
            SELECT ev.id_evento, ev.id_visita, ev.data_visita,
                   v.titolo AS titolo_visita,
                   g.nome AS nome_guida, g.cognome AS cognome_guida
            FROM eventi_visite ev
            JOIN visite v ON ev.id_visita = v.id_visita
            JOIN eventi e ON ev.id_evento = e.id_evento
            JOIN guide g ON e.guida = g.id_guida
            ORDER BY ev.data_visita DESC
        ";

        $stmt = $this->db->query($sql);
        $schedules = $stmt->fetchAll();

        require dirname(__DIR__, 2) . '/App/View/schedules/index.php';
    }

    // Mostra il form per aggiungere una nuova programmazione
    public function createForm()
    {
        $visite = $this->db->query("SELECT * FROM visite")->fetchAll();
        $eventi = $this->db->query("SELECT e.*, g.nome, g.cognome FROM eventi e JOIN guide g ON e.guida = g.id_guida")->fetchAll();

        require dirname(__DIR__, 2) . '/App/View/schedules/create.php';
    }

    // Elabora la creazione di una nuova programmazione
    public function create()
    {
        $idVisita = $_POST['id_visita'] ?? null;
        $idEvento = $_POST['id_evento'] ?? null;
        $dataVisita = $_POST['data_visita'] ?? null;

        if ($idVisita && $idEvento && $dataVisita) {
            $stmt = $this->db->prepare("INSERT INTO eventi_visite (id_visita, id_evento, data_visita) VALUES (?, ?, ?)");
            $stmt->execute([$idVisita, $idEvento, $dataVisita]);

            header("Location: /Artifex/admin/schedules");
            exit;
        } else {
            echo "Tutti i campi sono obbligatori.";
        }
    }

    // Elimina una programmazione
    public function delete()
    {
        $idVisita = $_POST['id_visita'] ?? null;
        $idEvento = $_POST['id_evento'] ?? null;

        if ($idVisita && $idEvento) {
            $stmt = $this->db->prepare("DELETE FROM eventi_visite WHERE id_visita = ? AND id_evento = ?");
            $stmt->execute([$idVisita, $idEvento]);

            header("Location: /Artifex/admin/schedules");
            exit;
        } else {
            echo "ID visita ed evento mancanti.";
        }
    }
}
