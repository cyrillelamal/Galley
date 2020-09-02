import React from 'react'
import UserContext from "../contexts/UserContext";
import {Redirect} from "react-router-dom";
import axios from 'axios'
import Task from "../components/Task";
import TaskForm from "../components/TaskForm";
import Sorting from "../components/Sorting";
import Listing from "../components/Listing";
import ListingForm from "../components/ListingForm";

class Dashboard extends React.Component {
    constructor(props) {
        super(props);

        const nullListing = {
            id: -1,
            name: 'All tasks'
        }

        this.state = {
            tasks: [],
            listings: [nullListing],

            currentListingId: nullListing.id,

            sorting: {
                by: 'expires_at',
                order: 'asc'
            }
        }

        this.setSorting = this.setSorting.bind(this)
        this.addTask = this.addTask.bind(this)
        this.removeTask = this.removeTask.bind(this)
        this.addListing = this.addListing.bind(this)
        this.removeListing = this.removeListing.bind(this)
        this.setCurrentListingId = this.setCurrentListingId.bind(this)
    }

    componentDidMount() {
        this.loadTask()
        this.loadListings()
    }

    loadTask() {
        axios.get('/api/tasks')
            .then(res => res.data)
            .then(tasks => tasks && this.setState({tasks}))
    }

    loadListings() {
        axios.get('/api/listings')
            .then(res => res.data)
            .then(listings => listings && this.setState((state) => {
                return {
                    listings: [...state.listings, ...listings]
                }
            }))
    }

    setSorting(sorting) {
        this.setState({sorting})
    }

    addTask(task) {
        this.setState((state) => {
            return {
                tasks: [...state.tasks, task]
            }
        })
    }

    removeTask(task) {
        const {id} = task

        axios.delete(`/api/tasks/${id}`)
            .then(res => {
                    if (res.status === 200) {
                        this.setState((state) => {
                            return {
                                tasks: state.tasks.filter(task => task.id !== id)
                            }
                        })
                    }
                }
            )
    }

    addListing(listing) {
        this.setState((state) => {
            return {
                listings: [...state.listings, listing]
            }
        })
    }

    removeListing(listing) {
        const {id} = listing

        axios.delete(`/api/tasks/${id}`)
            .then(res => {
                    if (res.status === 200) {
                        this.setState((state) => {
                            return {
                                listings: state.listings.filter(l => l.id !== id)
                            }
                        })
                    }
                }
            )
    }

    setCurrentListingId(id) {
        this.setState({currentListingId: id})
    }

    render() {
        const {user} = this.context
        if (!user) {
            return <Redirect to={'/'}/>
        }

        const listings = this.state.listings.map(l => (
            <Listing
                key={l.id} listing={l}
                removeListing={this.removeListing}
                setCurrentListingId={this.setCurrentListingId}
            />
        ))

        const tasks = this.state.tasks
            .filter(({listing_id}) => this.state.currentListingId === -1 || listing_id === this.state.currentListingId)
            .map(task => (
                <Task
                    key={task.id}
                    task={task}
                    removeTask={this.removeTask}
                />
            ))

        return (
            <section className="section">
                <div className="container is-fullwidth">
                    <div className="columns">
                        <div className="column is-3">
                            <ListingForm addListing={this.addListing}/>
                            <nav className="panel mt-2">
                                <p className="panel-heading">
                                    Listings
                                </p>
                                {listings}
                            </nav>
                        </div>
                        <div className="column is-9">
                            <Sorting setSorting={this.setSorting}/>
                            <TaskForm
                                addTask={this.addTask}
                                currentListingId={this.state.currentListingId}
                                listings={this.state.listings}
                            />
                            {tasks}
                        </div>
                    </div>
                </div>
            </section>
        )
    }
}

Dashboard.contextType = UserContext

export default Dashboard
