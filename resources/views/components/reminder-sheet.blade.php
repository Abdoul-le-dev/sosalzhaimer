<div id="remSheet" class="sheet">
    <div class="handle"></div>
    <h4>Nouveau rappel</h4>
    
    <form id="reminderForm" onsubmit="saveReminder(event)">
        {{-- Titre --}}
        <div class="field">
            <label for="remTitleInput">Titre</label>
            <input id="remTitleInput" 
                   name="title"
                   class="input" 
                   type="text"
                   placeholder="Ex. Médicament, RDV..."
                   required
                   aria-required="true">
        </div>
        
        {{-- Date et Heure --}}
        <div class="fieldRow">
            <div class="field">
                <label for="remDate">Date</label>
                <input id="remDate" 
                       name="date"
                       type="date" 
                       class="input"
                       required
                       aria-required="true">
            </div>
            <div class="field">
                <label for="remTime">Heure</label>
                <input id="remTime" 
                       name="time"
                       type="time" 
                       class="input"
                       required
                       aria-required="true">
            </div>
        </div>
        
        {{-- Répétition --}}
        <div class="field">
            <label for="remRepeat">Répétition</label>
            <select id="remRepeat" name="repeat" class="input" aria-label="Type de répétition">
                <option value="none">Aucune</option>
                <option value="daily">Quotidien</option>
                <option value="weekly">Hebdomadaire</option>
            </select>
        </div>
        
        {{-- Boutons --}}
        <div class="btnRow">
            <button type="submit" class="btn" aria-label="Enregistrer le rappel">
                Enregistrer
            </button>
            <button type="button" 
                    class="btn secondary" 
                    onclick="closeReminderSheet()"
                    aria-label="Annuler">
                Annuler
            </button>
        </div>
    </form>
</div>