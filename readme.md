FacEssential
* **FacEssential** is a Core for **PMMP**, it gathers all kind of plugins needed to create a faction server. It was created from scratch by **Clouds#0667**. 
---------------
Available orders 
----------------
* Table of commands below, the * character means all.

| Command     | Description                                   | Permission    |
|-------------|-----------------------------------------------|---------------|
| `/tpa`      | Allows you to teleport to a player.           | *             |
| `/tpahere`  | Allows to teleport to you.                    | *             |
| `/tpaccept` | Allows you to accept a teleportation request. | *             |
| `/tpdeny`   | Allows you to refuse a teleportation request. | *             |
| `/feed`     | Allows you to feed a player or yourself.      | use.feed      |
| `/heal`     | Allows you to heal a player or yourself.      | use.heal      |
-----
Here is the configuration file
------------------------------
```yaml
# Connect/Disconnect Message
connect-disconnect:
  # Message format. (popup, tip or message)
  format: "popup"
  # Connection message. {player} = name of player (broadcast)
  connect-message: "§7[§2+§7] §2{player}"
  # Disconnect message. {player} = name of player (broadcast)
  disconnect-message: "§7[§4-§7] §4{player}"
  # First connection message. {player} = name of player (broadcast message)
  first-connect-message: "§e{player} §fhas just logged on for the first time, welcome!"

# Combat Logger
combat-logger:
  # How long does it take for the combat logger to come off? (in second)
  cooldown-combat-logger: 15
  # Do you want to display a time remaining message for the combat logger?
  display-cooldown-combat-logger: true

# Teleport
teleport:
  # Do you want players to be immune to each teleportation for a certain time?
  immune-players: false
  # If so, for how long?
  immune-time: 15

# Damage
# Do you allow fall damage?
fall-damage: true
#Do you allow lava damage?
lava-damage: true

# Other
# Set the commands to disabled here.
disable-command:
  - "about"
```
----------------

If you have any **ideas** for additions or a **bug** to report, please join the server discord by clicking [here](https://discord.gg/kARpD3DsdU).


