var $;
var dataTable;
export function setJQuery(jq) {
    $ = jq;
    dataTable = jq.fn.dataTable;
}
var StateRestore = /** @class */ (function () {
    function StateRestore(settings, opts, identifier, state, isPreDefined, successCallback) {
        if (state === void 0) { state = undefined; }
        if (isPreDefined === void 0) { isPreDefined = false; }
        if (successCallback === void 0) { successCallback = function () { return null; }; }
        // Check that the required version of DataTables is included
        if (!dataTable || !dataTable.versionCheck || !dataTable.versionCheck('1.10.0')) {
            throw new Error('StateRestore requires DataTables 1.10 or newer');
        }
        // Check that Select is included
        // eslint-disable-next-line no-extra-parens
        if (!dataTable.Buttons) {
            throw new Error('StateRestore requires Buttons');
        }
        var table = new dataTable.Api(settings);
        this.classes = $.extend(true, {}, StateRestore.classes);
        // Get options from user
        this.c = $.extend(true, {}, StateRestore.defaults, opts);
        this.s = {
            dt: table,
            identifier: identifier,
            isPreDefined: isPreDefined,
            savedState: null,
            tableId: state && state.stateRestore ? state.stateRestore.tableId : undefined
        };
        this.dom = {
            background: $('<div class="' + this.classes.background + '"/>'),
            closeButton: $('<div class="' + this.classes.closeButton + '">x</div>'),
            confirmation: $('<div class="' + this.classes.confirmation + '"/>'),
            confirmationButton: $('<button class="' + this.classes.confirmationButton + ' ' + this.classes.dtButton + '">'),
            confirmationTitleRow: $('<div class="' + this.classes.confirmationTitleRow + '"></div>'),
            dtContainer: $(this.s.dt.table().container()),
            duplicateError: $('<span class="' + this.classes.modalError + '">' +
                this.s.dt.i18n('stateRestore.duplicateError', this.c.i18n.duplicateError) +
                '</span>'),
            emptyError: $('<span class="' + this.classes.modalError + '">' +
                this.s.dt.i18n('stateRestore.emptyError', this.c.i18n.emptyError) +
                '</span>'),
            removeContents: $('<div class="' + this.classes.confirmationText + '"><span>' +
                this.s.dt
                    .i18n('stateRestore.removeConfirm', this.c.i18n.removeConfirm)
                    .replace(/%s/g, this.s.identifier) +
                '</span></div>'),
            removeError: $('<span class="' + this.classes.modalError + '">' +
                this.s.dt.i18n('stateRestore.removeError', this.c.i18n.removeError) +
                '</span>'),
            removeTitle: $('<h2 class="' + this.classes.confirmationTitle + '">' +
                this.s.dt.i18n('stateRestore.removeTitle', this.c.i18n.removeTitle) +
                '</h2>'),
            renameContents: $('<div class="' + this.classes.confirmationText + ' ' + this.classes.renameModal + '">' +
                '<label class="' + this.classes.confirmationMessage + '">' +
                this.s.dt
                    .i18n('stateRestore.renameLabel', this.c.i18n.renameLabel)
                    .replace(/%s/g, this.s.identifier) +
                '</label>' +
                '</div>'),
            renameInput: $('<input class="' + this.classes.input + '" type="text"></input>'),
            renameTitle: $('<h2 class="' + this.classes.confirmationTitle + '">' +
                this.s.dt.i18n('stateRestore.renameTitle', this.c.i18n.renameTitle) +
                '</h2>')
        };
        // When a StateRestore instance is created the current state of the table should also be saved.
        this.save(state, successCallback);
    }
    /**
     * Removes a state from storage and then triggers the dtsr-remove event
     * so that the StateRestoreCollection class can remove it's references as well.
     *
     * @param skipModal Flag to indicate if the modal should be skipped or not
     */
    StateRestore.prototype.remove = function (skipModal) {
        var _a;
        var _this = this;
        if (skipModal === void 0) { skipModal = false; }
        // Check if removal of states is allowed
        if (!this.c.remove) {
            return false;
        }
        var removeFunction;
        var ajaxData = {
            action: 'remove',
            stateRestore: (_a = {},
                _a[this.s.identifier] = this.s.savedState,
                _a)
        };
        var successCallback = function () {
            _this.dom.confirmation.trigger('dtsr-remove');
            $(_this.s.dt.table().node()).trigger('stateRestore-change');
            _this.dom.background.click();
            _this.dom.confirmation.remove();
            $(document).unbind('keyup', function (e) { return _this._keyupFunction(e); });
            _this.dom.confirmationButton.off('click');
        };
        // If the remove is not happening over ajax remove it from local storage and then trigger the event
        if (!this.c.ajax) {
            removeFunction = function () {
                try {
                    localStorage.removeItem('DataTables_stateRestore_' + _this.s.identifier + '_' + location.pathname +
                        (_this.s.tableId ? '_' + _this.s.tableId : ''));
                    successCallback();
                }
                catch (e) {
                    _this.dom.confirmation.children('.' + _this.classes.modalError).remove();
                    _this.dom.confirmation.append(_this.dom.removeError);
                    return 'remove';
                }
                return true;
            };
        }
        // Ajax property has to be a string, not just true
        // Also only want to save if the table has been initialised and the states have been loaded in
        else if (typeof this.c.ajax === 'string' && this.s.dt.settings()[0]._bInitComplete) {
            removeFunction = function () {
                $.ajax({
                    data: ajaxData,
                    success: successCallback,
                    type: 'POST',
                    url: _this.c.ajax
                });
                return true;
            };
        }
        else if (typeof this.c.ajax === 'function') {
            removeFunction = function () {
                if (typeof _this.c.ajax === 'function') {
                    _this.c.ajax.call(_this.s.dt, ajaxData, successCallback);
                }
                return true;
            };
        }
        // If the modal is to be skipped then remove straight away
        if (skipModal) {
            this.dom.confirmation.appendTo(this.dom.dtContainer);
            $(this.s.dt.table().node()).trigger('dtsr-modal-inserted');
            removeFunction();
            this.dom.confirmation.remove();
        }
        // Otherwise display the modal
        else {
            this._newModal(this.dom.removeTitle, this.s.dt.i18n('stateRestore.removeSubmit', this.c.i18n.removeSubmit), removeFunction, this.dom.removeContents);
        }
        return true;
    };
    /**
     * Compares the state held within this instance with a state that is passed in
     *
     * @param state The state that is to be compared against
     * @returns boolean indicating if the states match
     */
    StateRestore.prototype.compare = function (state) {
        // Order
        if (!this.c.saveState.order) {
            state.order = undefined;
        }
        // Search
        if (!this.c.saveState.search) {
            state.search = undefined;
        }
        // Columns
        if (this.c.saveState.columns && state.columns) {
            for (var i = 0, ien = state.columns.length; i < ien; i++) {
                // Visibility
                if (typeof this.c.saveState.columns !== 'boolean' && !this.c.saveState.columns.visible) {
                    state.columns[i].visible = undefined;
                }
                // Search
                if (typeof this.c.saveState.columns !== 'boolean' && !this.c.saveState.columns.search) {
                    state.columns[i].search = undefined;
                }
            }
        }
        else if (!this.c.saveState.columns) {
            state.columns = undefined;
        }
        // Paging
        if (!this.c.saveState.paging) {
            state.page = undefined;
        }
        // SearchBuilder
        if (!this.c.saveState.searchBuilder) {
            state.searchBuilder = undefined;
        }
        // SearchPanes
        if (!this.c.saveState.searchPanes) {
            state.searchPanes = undefined;
        }
        // Select
        if (!this.c.saveState.select) {
            state.select = undefined;
        }
        // ColReorder
        if (!this.c.saveState.colReorder) {
            state.ColReorder = undefined;
        }
        // Scroller
        if (!this.c.saveState.scroller) {
            state.scroller = undefined;
            if (dataTable.Scroller !== undefined) {
                state.start = 0;
            }
        }
        // Paging
        if (!this.c.saveState.paging) {
            state.start = 0;
        }
        // Page Length
        if (!this.c.saveState.length) {
            state.length = undefined;
        }
        // Need to delete properties that we do not want to compare
        delete state.time;
        var copyState = this.s.savedState;
        delete copyState.time;
        delete copyState.c;
        delete copyState.stateRestore;
        // Perform a deep compare of the two state objects
        return this._deepCompare(state, copyState);
    };
    /**
     * Removes all of the dom elements from the document
     */
    StateRestore.prototype.destroy = function () {
        Object.values(this.dom).forEach(function (node) { return node.off().remove(); });
    };
    /**
     * Loads the state referenced by the identifier from storage
     *
     * @param state The identifier of the state that should be loaded
     * @returns the state that has been loaded
     */
    StateRestore.prototype.load = function () {
        var _this = this;
        var loadedState = this.s.savedState;
        var settings = this.s.dt.settings()[0];
        // Always want the states stored here to be loaded in - regardless of when they were created
        loadedState.time = +new Date();
        settings.oLoadedState = $.extend(true, {}, loadedState);
        // Click on a background if there is one to shut the collection
        $('div.dt-button-background').click();
        // Call the internal datatables function to implement the state on the table
        $.fn.dataTable.ext.oApi._fnImplementState(settings, loadedState, function () {
            var correctPaging = function (e, preSettings) {
                setTimeout(function () {
                    var currpage = preSettings._iDisplayStart / preSettings._iDisplayLength;
                    var intendedPage = loadedState.start / loadedState.length;
                    // If the paging is incorrect then we have to set it again so that it is correct
                    // This happens when a searchpanes filter is removed
                    // This has to happen in a timeout because searchpanes only deselects after a timeout
                    if (currpage >= 0 && intendedPage >= 0 && currpage !== intendedPage) {
                        _this.s.dt.page(intendedPage).draw(false);
                    }
                }, 50);
            };
            _this.s.dt.one('preDraw', correctPaging);
            _this.s.dt.draw(false);
        });
        return loadedState;
    };
    /**
     * Shows a modal that allows a state to be renamed
     *
     * @param newIdentifier Optional. The new identifier for this state
     */
    StateRestore.prototype.rename = function (newIdentifier, currentIdentifiers) {
        var _this = this;
        if (newIdentifier === void 0) { newIdentifier = null; }
        // Check if renaming of states is allowed
        if (!this.c.rename) {
            return;
        }
        var renameFunction = function () {
            var _a;
            if (newIdentifier === null) {
                var tempIdentifier = $('input.' + _this.classes.input.replace(/ /g, '.')).val();
                if (tempIdentifier.length === 0) {
                    _this.dom.confirmation.children('.' + _this.classes.modalError).remove();
                    _this.dom.confirmation.append(_this.dom.emptyError);
                    return 'empty';
                }
                else if (currentIdentifiers.includes(tempIdentifier)) {
                    _this.dom.confirmation.children('.' + _this.classes.modalError).remove();
                    _this.dom.confirmation.append(_this.dom.duplicateError);
                    return 'duplicate';
                }
                else {
                    newIdentifier = tempIdentifier;
                }
            }
            var ajaxData = {
                action: 'rename',
                stateRestore: (_a = {},
                    _a[_this.s.identifier] = newIdentifier,
                    _a)
            };
            var successCallback = function () {
                _this.s.identifier = newIdentifier;
                _this.save(_this.s.savedState, function () { return null; }, false);
                _this.dom.removeContents = $('<div class="' + _this.classes.confirmationText + '"><span>' +
                    _this.s.dt
                        .i18n('stateRestore.removeConfirm', _this.c.i18n.removeConfirm)
                        .replace(/%s/g, _this.s.identifier) +
                    '</span></div>');
                _this.dom.confirmation.trigger('dtsr-rename');
                _this.dom.background.click();
                _this.dom.confirmation.remove();
                $(document).unbind('keyup', function (e) { return _this._keyupFunction(e); });
                _this.dom.confirmationButton.off('click');
            };
            if (!_this.c.ajax) {
                try {
                    localStorage.removeItem('DataTables_stateRestore_' + _this.s.identifier + '_' + location.pathname +
                        (_this.s.tableId ? '_' + _this.s.tableId : ''));
                    successCallback();
                }
                catch (e) {
                    _this.dom.confirmation.children('.' + _this.classes.modalError).remove();
                    _this.dom.confirmation.append(_this.dom.removeError);
                    return false;
                }
            }
            else if (typeof _this.c.ajax === 'string' && _this.s.dt.settings()[0]._bInitComplete) {
                $.ajax({
                    data: ajaxData,
                    success: successCallback,
                    type: 'POST',
                    url: _this.c.ajax
                });
            }
            else if (typeof _this.c.ajax === 'function') {
                _this.c.ajax.call(_this.s.dt, ajaxData, successCallback);
            }
            return true;
        };
        // Check if a new identifier has been provided, if so no need for a modal
        if (newIdentifier !== null) {
            if (currentIdentifiers.includes(newIdentifier)) {
                throw new Error(this.s.dt.i18n('stateRestore.duplicateError', this.c.i18n.duplicateError));
            }
            else if (newIdentifier.length === 0) {
                throw new Error(this.s.dt.i18n('stateRestore.emptyError', this.c.i18n.emptyError));
            }
            else {
                this.dom.confirmation.appendTo(this.dom.dtContainer);
                $(this.s.dt.table().node()).trigger('dtsr-modal-inserted');
                renameFunction();
                this.dom.confirmation.remove();
            }
        }
        else {
            this.dom.renameInput.val(this.s.identifier);
            this.dom.renameContents.append(this.dom.renameInput);
            this._newModal(this.dom.renameTitle, this.s.dt.i18n('stateRestore.renameButton', this.c.i18n.renameButton), renameFunction, this.dom.renameContents);
        }
    };
    /**
     * Saves the tables current state using the identifier that is passed in.
     *
     * @param state Optional. If provided this is the state that will be saved rather than using the current state
     */
    StateRestore.prototype.save = function (state, passedSuccessCallback, callAjax) {
        var _a;
        var _this = this;
        if (callAjax === void 0) { callAjax = true; }
        // Check if saving states is allowed
        if (!this.c.save) {
            if (passedSuccessCallback) {
                passedSuccessCallback.call(this);
            }
            return;
        }
        // this.s.dt.state.save();
        var savedState;
        // If no state has been provided then create a new one from the current state
        this.s.dt.state.save();
        if (state === undefined) {
            savedState = this.s.dt.state();
        }
        else if (typeof state !== 'object') {
            return;
        }
        else {
            savedState = state;
        }
        if (savedState.stateRestore) {
            savedState.stateRestore.isPreDefined = this.s.isPreDefined;
            savedState.stateRestore.state = this.s.identifier;
            savedState.stateRestore.tableId = this.s.tableId;
        }
        else {
            savedState.stateRestore = {
                isPreDefined: this.s.isPreDefined,
                state: this.s.identifier,
                tableId: this.s.tableId
            };
        }
        this.s.savedState = savedState;
        // Order
        if (!this.c.saveState.order) {
            this.s.savedState.order = undefined;
        }
        // Search
        if (!this.c.saveState.search) {
            this.s.savedState.search = undefined;
        }
        // Columns
        if (this.c.saveState.columns && this.s.savedState.columns) {
            for (var i = 0, ien = this.s.savedState.columns.length; i < ien; i++) {
                // Visibility
                if (typeof this.c.saveState.columns !== 'boolean' && !this.c.saveState.columns.visible) {
                    this.s.savedState.columns[i].visible = undefined;
                }
                // Search
                if (typeof this.c.saveState.columns !== 'boolean' && !this.c.saveState.columns.search) {
                    this.s.savedState.columns[i].search = undefined;
                }
            }
        }
        else if (!this.c.saveState.columns) {
            this.s.savedState.columns = undefined;
        }
        // SearchBuilder
        if (!this.c.saveState.searchBuilder) {
            this.s.savedState.searchBuilder = undefined;
        }
        // SearchPanes
        if (!this.c.saveState.searchPanes) {
            this.s.savedState.searchPanes = undefined;
        }
        // Select
        if (!this.c.saveState.select) {
            this.s.savedState.select = undefined;
        }
        // ColReorder
        if (!this.c.saveState.colReorder) {
            this.s.savedState.ColReorder = undefined;
        }
        // Scroller
        if (!this.c.saveState.scroller) {
            this.s.savedState.scroller = undefined;
            if (dataTable.Scroller !== undefined) {
                this.s.savedState.start = 0;
            }
        }
        // Paging
        if (!this.c.saveState.paging) {
            this.s.savedState.start = 0;
        }
        // Page Length
        if (!this.c.saveState.length) {
            this.s.savedState.length = undefined;
        }
        this.s.savedState.c = this.c;
        // Need to remove the parent reference before we save the state
        // Its not needed to rebuild, but it does cause a circular reference when converting to JSON
        if (this.s.savedState.c.splitSecondaries.length) {
            for (var _i = 0, _b = this.s.savedState.c.splitSecondaries; _i < _b.length; _i++) {
                var secondary = _b[_i];
                if (secondary.parent) {
                    secondary.parent = undefined;
                }
            }
        }
        // If the state is predefined there is no need to save it over ajax or to local storage
        if (this.s.isPreDefined) {
            if (passedSuccessCallback) {
                passedSuccessCallback.call(this);
            }
            return;
        }
        var ajaxData = {
            action: 'save',
            stateRestore: (_a = {},
                _a[this.s.identifier] = this.s.savedState,
                _a)
        };
        var successCallback = function () {
            if (passedSuccessCallback) {
                passedSuccessCallback.call(_this);
            }
            _this.dom.confirmation.trigger('dtsr-save');
            $(_this.s.dt.table().node()).trigger('stateRestore-change');
        };
        if (!this.c.ajax) {
            localStorage.setItem('DataTables_stateRestore_' + this.s.identifier + '_' + location.pathname +
                (this.s.tableId ? '_' + this.s.tableId : ''), JSON.stringify(this.s.savedState));
            successCallback();
        }
        else if (typeof this.c.ajax === 'string' && callAjax) {
            if (this.s.dt.settings()[0]._bInitComplete) {
                $.ajax({
                    data: ajaxData,
                    success: successCallback,
                    type: 'POST',
                    url: this.c.ajax
                });
            }
            else {
                this.s.dt.one('init', function () {
                    $.ajax({
                        data: ajaxData,
                        success: successCallback,
                        type: 'POST',
                        url: _this.c.ajax
                    });
                });
            }
        }
        else if (typeof this.c.ajax === 'function' && callAjax) {
            this.c.ajax.call(this.s.dt, ajaxData, successCallback);
        }
    };
    /**
     * Performs a deep compare of two state objects, returning true if they match
     *
     * @param state1 The first object to compare
     * @param state2 The second object to compare
     * @returns boolean indicating if the objects match
     */
    StateRestore.prototype._deepCompare = function (state1, state2) {
        // Put keys and states into arrays as this makes the later code easier to work
        var states = [state1, state2];
        var keys = [Object.keys(state1).sort(), Object.keys(state2).sort()];
        // If scroller is included then we need to remove the start value
        //  as it can be different but yield the same results
        if (keys[0].includes('scroller')) {
            var startIdx = keys[0].indexOf('start');
            if (startIdx) {
                keys[0].splice(startIdx, 1);
            }
        }
        if (keys[1].includes('scroller')) {
            var startIdx = keys[1].indexOf('start');
            if (startIdx) {
                keys[1].splice(startIdx, 1);
            }
        }
        // We want to remove any private properties within the states
        for (var i = 0; i < keys[0].length; i++) {
            if (keys[0][i].indexOf('_') === 0) {
                keys[0].splice(i, 1);
                i--;
                continue;
            }
            // If scroller is included then we need to remove the following values
            //  as they can be different but yield the same results
            if (keys[0][i] === 'baseRowTop' ||
                keys[0][i] === 'baseScrollTop' ||
                keys[0][i] === 'scrollTop' ||
                (!this.c.saveState.paging && keys[0][i] === 'page')) {
                keys[0].splice(i, 1);
                i--;
                continue;
            }
        }
        for (var i = 0; i < keys[1].length; i++) {
            if (keys[1][i].indexOf('_') === 0) {
                keys[1].splice(i, 1);
                i--;
                continue;
            }
            if (keys[1][i] === 'baseRowTop' ||
                keys[1][i] === 'baseScrollTop' ||
                keys[1][i] === 'scrollTop' ||
                (!this.c.saveState.paging && keys[0][i] === 'page')) {
                keys[1].splice(i, 1);
                i--;
                continue;
            }
        }
        if (keys[0].length === 0 && keys[1].length > 0 ||
            keys[1].length === 0 && keys[0].length > 0) {
            return false;
        }
        // We are only going to compare the keys that are common between both states
        for (var i = 0; i < keys[0].length; i++) {
            if (!keys[1].includes(keys[0][i])) {
                keys[0].splice(i, 1);
                i--;
            }
        }
        for (var i = 0; i < keys[1].length; i++) {
            if (!keys[0].includes(keys[1][i])) {
                keys[1].splice(i, 1);
                i--;
            }
        }
        // Then each key and value has to be checked against each other
        for (var i = 0; i < keys[0].length; i++) {
            // If the keys dont equal, or their corresponding types are different we can return false
            if (keys[0][i] !== keys[1][i] || typeof states[0][keys[0][i]] !== typeof states[1][keys[1][i]]) {
                return false;
            }
            // If the type is an object then further deep comparisons are required
            if (typeof states[0][keys[0][i]] === 'object') {
                if (!this._deepCompare(states[0][keys[0][i]], states[1][keys[1][i]])) {
                    return false;
                }
            }
            else if (typeof states[0][keys[0][i]] === 'number' && typeof states[1][keys[1][i]] === 'number') {
                if (Math.round(states[0][keys[0][i]]) !== Math.round(states[1][keys[1][i]])) {
                    return false;
                }
            }
            // Otherwise we can just check the value
            else if (states[0][keys[0][i]] !== states[1][keys[1][i]]) {
                return false;
            }
        }
        // If we get all the way to here there are no differences so return true for this object
        return true;
    };
    StateRestore.prototype._keyupFunction = function (e) {
        // If enter same action as pressing the button
        if (e.key === 'Enter') {
            this.dom.confirmationButton.click();
        }
        // If escape close modal
        else if (e.key === 'Escape') {
            $('div.' + this.classes.background.replace(/ /g, '.')).click();
        }
    };
    /**
     * Creates a new confirmation modal for the user to approve an action
     *
     * @param title The title that is to be displayed at the top of the modal
     * @param buttonText The text that is to be displayed in the confirmation button of the modal
     * @param buttonAction The action that should be taken when the confirmation button is pressed
     * @param modalContents The contents for the main body of the modal
     */
    StateRestore.prototype._newModal = function (title, buttonText, buttonAction, modalContents) {
        var _this = this;
        this.dom.background.appendTo(this.dom.dtContainer);
        this.dom.confirmationTitleRow.empty().append(title);
        this.dom.confirmationButton.html(buttonText);
        this.dom.confirmation
            .empty()
            .append(this.dom.confirmationTitleRow)
            .append(modalContents)
            .append($('<div class="' + this.classes.confirmationButtons + '"></div>')
            .append(this.dom.confirmationButton))
            .appendTo(this.dom.dtContainer);
        $(this.s.dt.table().node()).trigger('dtsr-modal-inserted');
        var inputs = modalContents.children('input');
        // If there is an input focus on that
        if (inputs.length > 0) {
            $(inputs[0]).focus();
        }
        // Otherwise focus on the confirmation button
        else {
            this.dom.confirmationButton.focus();
        }
        var background = $('div.' + this.classes.background.replace(/ /g, '.'));
        if (this.c.modalCloseButton) {
            this.dom.confirmation.append(this.dom.closeButton);
            this.dom.closeButton.on('click', function () { return background.click(); });
        }
        // When the button is clicked, call the appropriate action,
        // remove the background and modal from the screen and unbind the keyup event.
        this.dom.confirmationButton.on('click', function () { return buttonAction(); });
        this.dom.confirmation.on('click', function (e) {
            e.stopPropagation();
        });
        // When the button is clicked, remove the background and modal from the screen and unbind the keyup event.
        background.one('click', function () {
            _this.dom.background.remove();
            _this.dom.confirmation.remove();
            $(document).unbind('keyup', function (e) { return _this._keyupFunction(e); });
        });
        $(document).on('keyup', function (e) { return _this._keyupFunction(e); });
    };
    /**
     * Convert from camelCase notation to the internal Hungarian.
     * We could use the Hungarian convert function here, but this is cleaner
     *
     * @param {object} obj Object to convert
     * @returns {object} Inverted object
     * @memberof DataTable#oApi
     */
    StateRestore.prototype._searchToHung = function (obj) {
        return {
            bCaseInsensitive: obj.caseInsensitive,
            bRegex: obj.regex,
            bSmart: obj.smart,
            sSearch: obj.search
        };
    };
    StateRestore.version = '1.1.1';
    StateRestore.classes = {
        background: 'dtsr-background',
        closeButton: 'dtsr-popover-close',
        confirmation: 'dtsr-confirmation',
        confirmationButton: 'dtsr-confirmation-button',
        confirmationButtons: 'dtsr-confirmation-buttons',
        confirmationMessage: 'dtsr-confirmation-message dtsr-name-label',
        confirmationText: 'dtsr-confirmation-text',
        confirmationTitle: 'dtsr-confirmation-title',
        confirmationTitleRow: 'dtsr-confirmation-title-row',
        dtButton: 'dt-button',
        input: 'dtsr-input',
        modalError: 'dtsr-modal-error',
        renameModal: 'dtsr-rename-modal'
    };
    StateRestore.defaults = {
        _createInSaved: false,
        ajax: false,
        create: true,
        creationModal: false,
        i18n: {
            creationModal: {
                button: 'Create',
                colReorder: 'Column Order:',
                columns: {
                    search: 'Column Search:',
                    visible: 'Column Visibility:'
                },
                length: 'Page Length:',
                name: 'Name:',
                order: 'Sorting:',
                paging: 'Paging:',
                scroller: 'Scroll Position:',
                search: 'Search:',
                searchBuilder: 'SearchBuilder:',
                searchPanes: 'SearchPanes:',
                select: 'Select:',
                title: 'Create New State',
                toggleLabel: 'Includes:'
            },
            duplicateError: 'A state with this name already exists.',
            emptyError: 'Name cannot be empty.',
            emptyStates: 'No saved states',
            removeConfirm: 'Are you sure you want to remove %s?',
            removeError: 'Failed to remove state.',
            removeJoiner: ' and ',
            removeSubmit: 'Remove',
            removeTitle: 'Remove State',
            renameButton: 'Rename',
            renameLabel: 'New Name for %s:',
            renameTitle: 'Rename State'
        },
        modalCloseButton: true,
        remove: true,
        rename: true,
        save: true,
        saveState: {
            colReorder: true,
            columns: {
                search: true,
                visible: true
            },
            length: true,
            order: true,
            paging: true,
            scroller: true,
            search: true,
            searchBuilder: true,
            searchPanes: true,
            select: true
        },
        splitSecondaries: [
            'updateState',
            'renameState',
            'removeState'
        ],
        toggle: {
            colReorder: false,
            columns: {
                search: false,
                visible: false
            },
            length: false,
            order: false,
            paging: false,
            scroller: false,
            search: false,
            searchBuilder: false,
            searchPanes: false,
            select: false
        }
    };
    return StateRestore;
}());
export default StateRestore;
