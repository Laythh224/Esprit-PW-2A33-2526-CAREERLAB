<?php

class CandidatureAnalyzer {

    // Liste des mots-clés de compétences techniques à rechercher
    private $competences_cles = [
        // Informatique
        'php', 'javascript', 'js', 'html', 'css', 'sql', 'mysql', 'python', 'java', 
        'c++', 'c#', 'react', 'angular', 'vue', 'laravel', 'symfony', 'git', 'docker', 
        'agile', 'scrum', 'node', 'nodejs', 'api', 'rest', 'nosql', 'mongodb',
        // Architecture
        'autocad', 'revit', 'bim', 'sketchup', 'photoshop', 'illustrator', 'archicad', 
        'lumion', 'urbanisme', 'design', 'maquette',
        // Economie & Finance
        'finance', 'comptabilité', 'marketing', 'management', 'gestion', 'audit', 
        'excel', 'statistiques', 'macroéconomie', 'microéconomie', 'analyse',
        // Electromécanique
        'automatisme', 'thermodynamique', 'hydraulique', 'pneumatique', 'électricité', 
        'mécanique', 'maintenance', 'robotique', 'cao', 'solidworks', 'catia', 'siemens'
    ];

    public function analyser($cv_texte, $offre_domaine, $offre_titre, $score_test) {
        $cv_texte_lower = strtolower($cv_texte);
        
        // 1. Extraction des compétences
        $competences_trouvees = [];
        foreach ($this->competences_cles as $comp) {
            // Recherche de mots entiers pour éviter les faux positifs
            if (preg_match('/\b' . preg_quote($comp, '/') . '\b/', $cv_texte_lower)) {
                $competences_trouvees[] = $comp;
            }
        }
        $nb_competences = count($competences_trouvees);

        // 2. Extraction des années d'expérience (très flexible)
        $annees_exp = 0;
        // Cherche n'importe quel chiffre suivi de "an", "ans", "année" ou "années"
        if (preg_match('/(\d+)\s*(ans?|années?)/i', $cv_texte, $matches)) {
            $annees_exp = (int)$matches[1];
        }

        // 2.bis Extraction de diplômes (Bonus)
        $diplomes = ['diplôme', 'diplome', 'master', 'licence', 'bac', 'ingénieur', 'ingenieur', 'doctorat'];
        $a_diplome = false;
        foreach ($diplomes as $dip) {
            if (preg_match('/\b' . preg_quote($dip, '/') . '\b/i', $cv_texte_lower)) {
                $a_diplome = true;
                break;
            }
        }

        // 3. Calcul du Score Global
        // (compétences * 10) + (expérience * 5) + (score_test * 10) + (diplome * 10)
        $score_ia = ($nb_competences * 10) + ($annees_exp * 5) + ($score_test * 10) + ($a_diplome ? 10 : 0);
        // On cap le score à 100 maximum pour avoir un pourcentage lisible
        if ($score_ia > 100) $score_ia = 100;

        // 4. Calcul de Compatibilité
        // Basé sur les compétences par rapport au titre/domaine
        $mots_offre = strtolower($offre_domaine . ' ' . $offre_titre);
        $compatibilite = 0;
        $competences_utiles = [];
        foreach ($competences_trouvees as $comp) {
            if (strpos($mots_offre, $comp) !== false) {
                $compatibilite += 20; // Chaque compétence utile donne 20%
                $competences_utiles[] = $comp;
            }
        }
        // Ajout d'un pourcentage de base lié au score général
        $compatibilite += ($score_ia * 0.5);
        if ($compatibilite > 100) $compatibilite = 100;

        // 5. Niveau
        if ($score_ia < 40) {
            $niveau = 'Débutant';
        } elseif ($score_ia < 75) {
            $niveau = 'Intermédiaire';
        } else {
            $niveau = 'Avancé';
        }

        // 6. Recommandation
        if ($compatibilite >= 70 && $score_ia >= 60) {
            $recommandation = 'Accepter';
        } elseif ($compatibilite >= 40 || $score_ia >= 40) {
            $recommandation = 'À revoir';
        } else {
            $recommandation = 'Refuser';
        }

        // 7. Feedback IA
        $feedback = "<div class='text-start'><strong>Analyse IA du profil :</strong><br><br>";
        if (!empty($competences_trouvees)) {
            $feedback .= "<span class='text-success'><i class='fas fa-check-circle'></i> Compétences détectées : " . implode(', ', array_map('ucfirst', $competences_trouvees)) . "</span><br>";
        } else {
            $feedback .= "<span class='text-danger'><i class='fas fa-times-circle'></i> Aucune compétence technique détectée. Pensez à lister vos outils et compétences clés.</span><br>";
        }

        if ($annees_exp > 0) {
            $feedback .= "<span class='text-success'><i class='fas fa-check-circle'></i> Expérience détectée : " . $annees_exp . " an(s)</span><br>";
        } else {
            $feedback .= "<span class='text-warning'><i class='fas fa-exclamation-triangle'></i> Suggestion : Mentionnez vos années d'expérience (ex: '8 ans').</span><br>";
        }

        if ($a_diplome) {
            $feedback .= "<span class='text-success'><i class='fas fa-check-circle'></i> Diplôme(s) mentionné(s) dans le CV.</span><br>";
        } else {
            $feedback .= "<span class='text-warning'><i class='fas fa-exclamation-triangle'></i> Suggestion : Précisez vos diplômes (Licence, Master...).</span><br>";
        }

        if ($score_test >= 3) {
            $feedback .= "<span class='text-success'><i class='fas fa-check-circle'></i> Bon score au test technique (" . $score_test . "/5).</span><br>";
        } else {
            $feedback .= "<span class='text-danger'><i class='fas fa-times-circle'></i> Score faible au test technique (" . $score_test . "/5).</span><br>";
        }
        $feedback .= "</div>";

        return [
            'score_ia' => $score_ia,
            'compatibilite' => $compatibilite,
            'niveau' => $niveau,
            'recommandation' => $recommandation,
            'feedback' => $feedback
        ];
    }
}
