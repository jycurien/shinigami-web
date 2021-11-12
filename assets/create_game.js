let capacity = 0;
let roomEdit = null;
let centerEdit = null;
let teams = ['solo'];
const playerHidden = document.querySelector("#game_player_hidden");
const playerName = document.querySelector("#game_player");
const gameRoom = document.querySelector("#game_room");
const gameCenter = document.querySelector("#game_center");
const gameCenterSelect = document.querySelector("select#game_center");
const displayPlayers = document.querySelector("#display-players");
const capacityReached = document.querySelector('.capacity-reached');
const submit = document.querySelector("#game_submit");
const userSuggestions = document.querySelector("#user-suggestions");

// Team
const teamBtn = document.querySelector("#game_team_btn");
const teamText = document.querySelector("#game_team_text");
const teamHidden = document.querySelector("#game_team_hidden");

window.addEventListener('DOMContentLoaded', () => {
    initialiseAddUserLinks();
    handleSuggestions();
    initialiseRoomAndCenterValues();
    resetRoomList();
    initialisePlayerHiddenField();

    // In case the user is an Admin we have to handle the Select changes on the centers and get the get the right rooms
    if (gameCenterSelect) {
        initOnChangeSelectCenterForAdmin();
        handleEditInitCenter();
    } else {
        getRoomsListForStaff();
    }

    handleCapacity();
    handleRemovePlayer();
    handleAddTeam();
    initialiseTeam();
});

function handleSuggestions() {
    playerName.addEventListener('keyup', () => {
        if("" === playerName.value) {
            resetSuggestions();
        } else {
            if (playerName.value.length > 3) {
                getPlayerSuggestion(playerName.value);
            }
        }
    });
}

async function getPlayerSuggestion(value) {
    const res = await fetch(`/admin/ajax/user-suggestion/${value}`);
    if (res.ok) {
        const suggestions = await res.json();
        showSuggestions(suggestions);
    }
}

function showSuggestions(response) {

    resetSuggestions();

    const addedUsers = getAddedUsers();

    if (response.users) {
        for (let user of response.users) {
            if(-1 === addedUsers.indexOf(user.id)) {
                const link = document.createElement('a');
                link.dataset.id = user.id;
                link.classList.add('badge', 'bg-info', 'rounded-pill', 'add-user');
                link.textContent = user.username;
                userSuggestions.appendChild(link);
            }
        }
    }
}

function resetSuggestions() {
    userSuggestions.innerHTML = '';
}

function resetPlayerGameField() {
    playerName.value = '';
    playerName.focus();
}

function getAddedUsers() {

    let initialVal = playerHidden.value;

    let users = initialVal.split(";").map(function(item) {
        let user = item.split("-")[0];
        return parseInt(user, 10);
    });

    // we delete the last element because it's NaN
    users.pop();

    return users;
}

function getAddedUsersWithTeam() {

    const initialVal = playerHidden.value;

    const usersWithTeam = initialVal.split(";");

    // we delete the last element because it's NaN
    usersWithTeam.pop();

    return usersWithTeam;
}

function initialiseAddUserLinks() {
    document.addEventListener('click', e => {
        if (e.target.classList.contains('add-user'))
        addUserToGame(e.target.dataset.id);
    });
}

function addUserToGame(id) {
    const addedUsers = getAddedUsers();
    let value = "";

    // we check if the id is already in the input to avoid having it twice
    if(-1 === addedUsers.indexOf(id)) {

        // we concat user and team number, default value => 0 (solo)
        playerHidden.value += `${id}-0;`;

        displayPlayer(id);
        checkCapacity();
    }

    // We reset the Input Player Game to let the user type another pseudo
    resetPlayerGameField();

    // when we add a user we need to remove the suggestions
    resetSuggestions();
}

async function displayPlayer(id, team = 0) {
    const res = await fetch(`/admin/ajax/find-user/${id}`);

    if (res.ok) {
        const user = await res.json();
        const teamList = getTeamList();
        const userThumb = document.createElement('div');
        userThumb.classList.add('col-md-3', 'user-thumb', 'position-relative');
        userThumb.dataset.userId = id;
        userThumb.innerHTML = `
            <button type="button" class="close position-absolute badge bg-danger" aria-label="Close">&times;</button>   
            <div class="pic"><img src="${user.picture}"></div> 
            <p>${user.username}</p>
            <select name="team_select" class="team_select form-control">
            ${teamList}
            </select>
        `;
        displayPlayers.appendChild(userThumb);
        //we select the team with team value
        displayPlayers.querySelector(`[data-user-id='${id}'] .team_select option[value='${team}']`).selected = 'selected';
    }
}

function getTeamList() {
    let list = '';
    for (let i = 0; i < teams.length; i++) {
        list += '<option value="'+i+'">'+teams[i]+'</option>';
    }
    return list;
}

function initOnChangeSelectCenterForAdmin() {
    gameCenterSelect.addEventListener('change',  (function() {
        getRoomsListForAdmin();
    }));
}

function getRoomsListForAdmin() {
    resetRoomList();
    const centerId = gameCenter.value;
    getRoomsAjax(centerId);
}

function getRoomsListForStaff() {
    // we get the center Id placed on data-center-id
    const centerId = gameCenter.dataset.centerId;
    getRoomsAjax(centerId);
}

async function getRoomsAjax(centerId) {
    if (typeof centerId !== 'undefined' && centerId) {
        const res = await  fetch(`/admin/ajax/get-rooms-by-center/${centerId}`);
        if (res.ok) {
            const rooms = await res.json();
            resetRoomList();

            rooms.forEach(r => {
                const option = document.createElement('option');
                option.value = r.id;
                option.dataset.capacity = r.capacity;
                option.textContent = `${r.name} (${r.capacity})`;
                gameRoom.appendChild(option);
            });

            handleEditInitRoom();

            setCapacity();
        }
    }
}

function initialiseRoomAndCenterValues() {

    // If the game is being modified, we save The Room value
    if (document.querySelector("#game_room option").dataset.room) {
        roomEdit = document.querySelector("#game_room option").dataset.room;
    }

    // If the game is being modified, we save The Center value
    if (document.querySelector("#game_room option").dataset.center) {
        centerEdit = document.querySelector("#game_room option").dataset.center;
    }
}

function handleEditInitRoom() {
    // This part is to initialise the room selected in the existing game
    if(null != roomEdit) {
        gameRoom.value = roomEdit;
        roomEdit = null
    }
}

function handleEditInitCenter() {
    // This part is to initialise the center selected in the existing game
    if(null != centerEdit) {
        gameCenter.value = centerEdit;
        getRoomsListForAdmin();
    }
}

function resetRoomList() {
    gameRoom.innerHTML = '';
}

function initialisePlayerHiddenField() {

    const usersWithTeam = getAddedUsersWithTeam();

    if(usersWithTeam.length > 0) {
        usersWithTeam.forEach(value => {
            const id = value.split('-')[0];
            const team = value.split('-')[1];
            displayPlayer(id, team);
        });
    }
}

function handleCapacity() {
    // Each time we change
    gameRoom.addEventListener('change',  (function() {
        setCapacity();
    }));
}

// We need to set the capacity each time we change the room
function setCapacity() {
    capacity = gameRoom.querySelector('option:checked').dataset.capacity;
    checkCapacity();
}

function checkCapacity() {
    if (getAddedUsers().length > capacity) {
        submit.disabled = true;
        capacityReached.classList.add('show');
    } else {
        submit.disabled = false;
        capacityReached.classList.remove('show');
    }
}

function handleRemovePlayer() {
    displayPlayers.addEventListener('click',function(e) {
        if (e.target.classList.contains('close')) {
            const parent = e.target.parentElement;
            const id = parent.dataset.userId;

            // we delete User From Player Hidden Type
            removePlayerFromPlayerHiddenString(id);

            // we delete User From display-players Div
            parent.remove();

            // We need to re-check capacity to enabled the submit button if needed
            checkCapacity();
        }
    });
}

function removePlayerFromPlayerHiddenString(id) {
    const initVal = playerHidden.value;
    let newValue;

    // Case of the number is at the beginning of the string
    // we need to use Regex for the Team
    if(0 === initVal.indexOf(id+'-')) {
        newValue = initVal.replace(new RegExp(id+"-[0-9]*;" ),'');
    }
    // Case of the number is in the string but NOT at the beginning
    else if (-1 !== initVal.indexOf(';'+id+'-')) {
        newValue = initVal.replace(new RegExp(";"+id+"-[0-9]*;" ),';');
    }

    playerHidden.value = newValue;
}

function initialiseTeam() {

    // if editing, we initialise teams Value, else we get solo value set at the top
    if("" !== teamHidden.value) {
        teams = teamHidden.value.split(";");
        // we add ";" at the end of the existing string
        teamHidden.value += ';';
    } else {
        // we initialise TeamHidden Field
        teamHidden.value = teams+';';
    }
}

function handleAddTeam() {

    teamBtn.addEventListener('click', function() {
        // we get the text field value
        const teamName = teamText.value;

        // We check if the value is more than 1 character
        if (teamName.length > 0 && (-1 === teams.indexOf(teamName))) {
            addTeamToTeams(teamName);
            addTeamToHiddenTeam(teamName);
            addTeamToSelect(teamName);
            // we reset the input
            resetTeam();
        }
    });
}

function addTeamToTeams(teamName) {
    teams.push(teamName);
}

function addTeamToHiddenTeam(teamName) {
    teamHidden.value += `${teamName};`;
}

function resetTeam() {
    teamText.value = '';
}

function addTeamToSelect(teamName) {
    // we get the index of the team in Teams Array

    const teamSelects = document.querySelectorAll("#display-players .team_select");
    if (teamSelects.length) {
        teamSelects.forEach((t) => {
            const index = teams.indexOf(teamName);
            const option = document.createElement('option');
            option.value = index;
            option.textContent = teamName;
            t.appendChild(option);
        })
    }

    displayPlayers.addEventListener('change', function(e) {
        if (e.target.classList.contains('team_select')) {
            // we set the team to the user
            const parent = e.target.parentElement;
            const id = parent.dataset.userId;
            updateTeamUser(id, e.target.value);
        }
    });
}

function updateTeamUser(id, index) {
    const initVal = playerHidden.value;
    let newValue;

    // Case of the number is at the beginning of the string
    // we need to use Regex for the Team
    if(0 === initVal.indexOf(id+'-')) {
        newValue = initVal.replace(new RegExp(id+"-[0-9]*;" ), id+"-"+index+';');
    }
    // Case of the number is in the string but NOT at the beginning
    else if (-1 !== initVal.indexOf(';'+id+'-')) {
        newValue = initVal.replace(new RegExp(";"+id+"-[0-9]*;" ), ';'+id+"-"+index+';');
    }

    playerHidden.value = newValue;
}
